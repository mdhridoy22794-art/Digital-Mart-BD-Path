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
        $productId = $request->query('product_id');
        $query = DigitalLink::with(['order', 'product'])->latest();

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $links = $query->paginate(30);
        $availableCount = DigitalLink::where('status', 'available')->count();
        $soldCount = DigitalLink::where('status', 'sold')->count();
        $products = Product::all();

        return view('admin.links', compact('links', 'filter', 'productId', 'availableCount', 'soldCount', 'products'));
    }

    public function storeLinks(Request $request)
    {
        $request->validate([
            'bulk_links' => 'required|string',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $rawText = $request->input('bulk_links');
        $lines = preg_split('/\r\n|\r|\n/', $rawText);
        $addedCount = 0;
        $duplicateCount = 0;

        $productId = $request->input('product_id');
        if (!$productId) {
            $product = Product::where('slug', 'gemini-pro-18m')->first() ?? Product::first();
            $productId = $product ? $product->id : 1;
        }

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
        Order::where('digital_link_id', $link->id)->update(['digital_link_id' => null]);
        $link->delete();

        return redirect()->route('admin.links')->with('success', 'লিংকটি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function clearUnsoldLinks(Request $request)
    {
        $productId = $request->input('product_id');
        $query = DigitalLink::where('status', 'available');
        if ($productId) {
            $query->where('product_id', $productId);
        }

        $count = $query->count();
        $query->delete();

        return redirect()->route('admin.links')->with('success', "স্টক থেকে সফলভাবে {$count}টি অবিক্রীত লিংক মুছে ফেলা হয়েছে।");
    }

    public function bulkDeleteLinks(Request $request)
    {
        $ids = $request->input('selected_links', []);
        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('admin.links')->with('error', 'কোনো লিংক নির্বাচন করা হয়নি!');
        }

        Order::whereIn('digital_link_id', $ids)->update(['digital_link_id' => null]);
        $count = DigitalLink::whereIn('id', $ids)->delete();

        return redirect()->route('admin.links')->with('success', "নির্বাচিত {$count}টি লিংক সফলভাবে মুছে ফেলা হয়েছে।");
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

    public function destroyOrder($id)
    {
        $order = Order::findOrFail($id);
        $orderNumber = $order->order_number;
        $order->delete();

        return redirect()->route('admin.orders')->with('success', "অর্ডার #{$orderNumber} সফলভাবে মুছে ফেলা হয়েছে।");
    }

    public function bulkDeleteOrders(Request $request)
    {
        $ids = $request->input('selected_orders', []);
        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('admin.orders')->with('error', 'কোনো অর্ডার নির্বাচন করা হয়নি!');
        }

        $count = Order::whereIn('id', $ids)->delete();

        return redirect()->route('admin.orders')->with('success', "নির্বাচিত {$count}টি অর্ডার সফলভাবে মুছে ফেলা হয়েছে।");
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

    // ================= PRODUCT CRUD MANAGEMENT =================

    public function products()
    {
        $products = Product::withCount(['availableLinks', 'soldLinks'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'subtitle' => 'nullable|string|max:255',
            'regular_price' => 'required|numeric|min:0',
            'offer_price' => 'required|numeric|min:0',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'bulk_links' => 'nullable|string',
        ]);

        $featuresArray = [];
        if ($request->filled('features')) {
            $rawLines = preg_split('/\r\n|\r|\n/', $request->input('features'));
            foreach ($rawLines as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) $featuresArray[] = $trimmed;
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = 'prod_' . time() . '_' . Str::random(5) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('images/products'), $imageName);
            $imagePath = 'images/products/' . $imageName;
        }

        $slug = $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('name'));
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        $product = Product::create([
            'slug' => $slug,
            'name' => $request->input('name'),
            'subtitle' => $request->input('subtitle'),
            'regular_price' => $request->input('regular_price'),
            'offer_price' => $request->input('offer_price'),
            'badge' => $request->input('badge') ?? 'NEW',
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
            'description' => $request->input('description'),
            'features' => $featuresArray,
            'image_path' => $imagePath,
        ]);

        if ($request->filled('bulk_links')) {
            $lines = preg_split('/\r\n|\r|\n/', $request->input('bulk_links'));
            foreach ($lines as $line) {
                $clean = trim($line);
                if (!empty($clean)) {
                    DigitalLink::create([
                        'product_id' => $product->id,
                        'link_url' => $clean,
                        'status' => 'available',
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'নতুন প্রোডাক্ট সফলভাবে তৈরি ও স্টোরে যুক্ত করা হয়েছে!');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $availableLinks = $product->availableLinks()->count();
        $soldLinks = $product->soldLinks()->count();

        return view('admin.products.edit', compact('product', 'availableLinks', 'soldLinks'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'regular_price' => 'required|numeric|min:0',
            'offer_price' => 'required|numeric|min:0',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'bulk_links' => 'nullable|string',
        ]);

        $featuresArray = [];
        if ($request->filled('features')) {
            $rawLines = preg_split('/\r\n|\r|\n/', $request->input('features'));
            foreach ($rawLines as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) $featuresArray[] = $trimmed;
            }
        }

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = 'prod_' . time() . '_' . Str::random(5) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('images/products'), $imageName);
            $product->image_path = 'images/products/' . $imageName;
        }

        $product->name = $request->input('name');
        $product->subtitle = $request->input('subtitle');
        $product->regular_price = $request->input('regular_price');
        $product->offer_price = $request->input('offer_price');
        $product->badge = $request->input('badge') ?? 'OFFER';
        $product->is_active = $request->has('is_active') ? (bool)$request->input('is_active') : false;
        $product->description = $request->input('description');
        if (!empty($featuresArray)) {
            $product->features = $featuresArray;
        }
        $product->save();

        $linksAdded = 0;
        if ($request->filled('bulk_links')) {
            $lines = preg_split('/\r\n|\r|\n/', $request->input('bulk_links'));
            foreach ($lines as $line) {
                $clean = trim($line);
                if (!empty($clean)) {
                    $exists = DigitalLink::where('link_url', $clean)->exists();
                    if (!$exists) {
                        DigitalLink::create([
                            'product_id' => $product->id,
                            'link_url' => $clean,
                            'status' => 'available',
                        ]);
                        $linksAdded++;
                    }
                }
            }
        }

        $msg = "প্রোডাক্ট '{$product->name}' সফলভাবে আপডেট করা হয়েছে!";
        if ($linksAdded > 0) {
            $msg .= " এবং {$linksAdded}টি নতুন লিংক স্টকে যুক্ত হয়েছে।";
        }

        return redirect()->route('admin.products.index')->with('success', $msg);
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;
        // Delete associated digital links
        $product->links()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "প্রোডাক্ট '{$name}' এবং এর সমস্ত লিংক সফলভাবে মুছে ফেলা হয়েছে।");
    }

    public function toggleProductStock($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        $statusText = $product->is_active ? 'ইন স্টক (In Stock)' : 'স্টক শেষ (Out of Stock)';
        return back()->with('success', "প্রোডাক্ট '{$product->name}' এর অবস্থা '{$statusText}' করা হয়েছে।");
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
