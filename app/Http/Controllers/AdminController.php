<?php

namespace App\Http\Controllers;

use App\Models\DigitalLink;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'স্বাগতম! আপনি সফলভাবে অ্যাডমিন প্যানেলে লগইন করেছেন।');
        }

        return back()->withErrors([
            'email' => 'প্রদত্ত ইমেইল বা পাসওয়ার্ডটি সঠিক নয়!',
        ])->withInput($request->only('email'));
    }

    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('amount');
        $availableLinks = DigitalLink::where('status', 'available')->count();
        $soldLinks = DigitalLink::where('status', 'sold')->count();

        $recentOrders = Order::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'availableLinks',
            'soldLinks',
            'recentOrders'
        ));
    }

    public function links(Request $request)
    {
        $filter = $request->query('status', 'all');
        $query = DigitalLink::latest();

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $links = $query->paginate(25);
        $availableCount = DigitalLink::where('status', 'available')->count();
        $soldCount = DigitalLink::where('status', 'sold')->count();

        return view('admin.links', compact('links', 'filter', 'availableCount', 'soldCount'));
    }

    public function storeLinks(Request $request)
    {
        $request->validate([
            'bulk_links' => 'required|string',
        ]);

        $rawText = $request->input('bulk_links');
        $lines = preg_split('/\r\n|\r|\n/', $rawText);
        $addedCount = 0;
        $duplicateCount = 0;

        $product = Product::where('slug', 'gemini-pro-18m')->first();
        $productId = $product ? $product->id : 1;

        foreach ($lines as $line) {
            $cleanLink = trim($line);
            if (empty($cleanLink)) {
                continue;
            }

            // Check if already exists in DB
            $exists = DigitalLink::where('link_url', $cleanLink)->exists();
            if ($exists) {
                $duplicateCount++;
                continue;
            }

            DigitalLink::create([
                'product_id' => $productId,
                'link_url' => $cleanLink,
                'status' => 'available',
            ]);
            $addedCount++;
        }

        $message = "সফলভাবে {$addedCount}টি নতুন লিংক স্টকে যুক্ত করা হয়েছে।";
        if ($duplicateCount > 0) {
            $message .= " ({$duplicateCount}টি ডুপ্লিকেট লিংক বাদ দেওয়া হয়েছে)";
        }

        return redirect()->route('admin.links')->with('success', $message);
    }

    public function deleteLink($id)
    {
        $link = DigitalLink::findOrFail($id);
        $link->delete();

        return redirect()->route('admin.links')->with('success', 'লিংকটি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function orders(Request $request)
    {
        $search = trim($request->input('search'));
        $query = Order::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_phone', 'like', "%{$search}%")
                  ->orWhere('trx_id', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        return view('admin.orders', compact('orders', 'search'));
    }

    public function settings()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $product = Product::where('slug', 'gemini-pro-18m')->first();

        return view('admin.settings', compact('settings', 'product'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', 'regular_price', 'offer_price']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        if ($request->has('regular_price') && $request->has('offer_price')) {
            $product = Product::where('slug', 'gemini-pro-18m')->first();
            if ($product) {
                $product->update([
                    'regular_price' => $request->input('regular_price'),
                    'offer_price' => $request->input('offer_price'),
                ]);
            }
        }

        return redirect()->route('admin.settings')->with('success', 'সকল সেটিংস সফলভাবে আপডেট ও সংরক্ষিত হয়েছে!');
    }

    public function gemini()
    {
        $product = Product::where('slug', 'gemini-pro-18m')->firstOrFail();
        $availableLinks = $product->availableLinks()->count();
        $soldLinks = $product->soldLinks()->count();
        $recentLinks = $product->links()->latest()->take(10)->get();

        return view('admin.gemini', compact('product', 'availableLinks', 'soldLinks', 'recentLinks'));
    }

    public function updateGemini(Request $request)
    {
        $product = Product::where('slug', 'gemini-pro-18m')->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'regular_price' => 'required|numeric|min:0',
            'offer_price' => 'required|numeric|min:0',
            'badge' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'bulk_links' => 'nullable|string',
        ]);

        // Process features
        $featuresArray = [];
        if ($request->filled('features')) {
            $rawLines = preg_split('/\r\n|\r|\n/', $request->input('features'));
            foreach ($rawLines as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) {
                    $featuresArray[] = $trimmed;
                }
            }
        }

        // Process Image Upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = 'gemini_' . time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('images/products'), $imageName);
            $product->image_path = 'images/products/' . $imageName;
        }

        $product->name = $request->input('name');
        $product->subtitle = $request->input('subtitle');
        $product->regular_price = $request->input('regular_price');
        $product->offer_price = $request->input('offer_price');
        $product->badge = $request->input('badge') ?? 'HOT DEAL';
        $product->description = $request->input('description');
        if (!empty($featuresArray)) {
            $product->features = $featuresArray;
        }
        $product->save();

        // Process Bulk Links if provided
        $linksAdded = 0;
        if ($request->filled('bulk_links')) {
            $linkLines = preg_split('/\r\n|\r|\n/', $request->input('bulk_links'));
            foreach ($linkLines as $line) {
                $cleanLink = trim($line);
                if (empty($cleanLink)) continue;

                $exists = DigitalLink::where('link_url', $cleanLink)->exists();
                if (!$exists) {
                    DigitalLink::create([
                        'product_id' => $product->id,
                        'link_url' => $cleanLink,
                        'status' => 'available',
                    ]);
                    $linksAdded++;
                }
            }
        }

        $msg = 'জেমিনাই প্রো অফার ও পোস্ট সফলভাবে আপডেট করা হয়েছে!';
        if ($linksAdded > 0) {
            $msg .= " এবং {$linksAdded}টি নতুন অ্যাক্টিভেশন লিংক স্টকে যুক্ত করা হয়েছে।";
        }

        return redirect()->route('admin.gemini')->with('success', $msg);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'বর্তমান পাসওয়ার্ড প্রদান করুন।',
            'password.required' => 'নতুন পাসওয়ার্ড প্রদান করুন।',
            'password.min' => 'নতুন পাসওয়ার্ড অন্তত ৬ অক্ষরের হতে হবে।',
            'password.confirmed' => 'নতুন পাসওয়ার্ড ও কনফার্ম পাসওয়ার্ড মেলেনি।',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ডটি সঠিক নয়!'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'আপনার অ্যাডমিন পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'আপনি সফলভাবে লগআউট করেছেন।');
    }
}
