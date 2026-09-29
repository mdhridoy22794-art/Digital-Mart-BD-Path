<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\DigitalLink;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $query = Product::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $allProducts = $query->get();

        // Gemini Pro is strictly #1 product as requested
        $gemini = Product::where('slug', 'gemini-pro-18m')->first();
        $landing = Product::where('slug', 'bangla-landing-page-bundle')->first();
        $duolingo = Product::where('slug', 'duolingo-super-12m')->first();
        $canva = Product::where('slug', 'canva-pro-1-year')->first();

        $trendingProducts = collect([$gemini, $landing, $duolingo, $canva])->filter();

        // 4 distinct digital products for Recent section
        $chatgpt = Product::where('slug', 'like', '%chatgpt%')->first();
        $capcut = Product::where('slug', 'like', '%capcut%')->first();
        $claude = Product::where('slug', 'like', '%claude%')->first();
        $office = Product::where('slug', 'like', '%office%')->first();

        $recentProducts = collect([$chatgpt, $capcut, $claude, $office])->filter();

        $stockCount = $gemini ? $gemini->stock_count : 0;

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('frontend.index', compact(
            'trendingProducts',
            'recentProducts',
            'allProducts',
            'gemini',
            'stockCount',
            'settings',
            'search'
        ));
    }

    public function productDetails($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $stockCount = $product->is_active ? $product->stock_count : 0;

        $relatedProducts = Product::where('id', '!=', $product->id)->take(4)->get();
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('frontend.product', compact('product', 'stockCount', 'relatedProducts', 'settings'));
    }

    public function processOrder(Request $request)
    {
        $validated = $request->validate([
            'product_slug' => 'nullable|string',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:bkash,nagad',
            'sender_phone' => 'required|string|max:20',
            'trx_id' => 'required|string|max:50',
            'screenshot' => 'nullable|image|max:5120',
        ]);

        $slug = $validated['product_slug'] ?? 'gemini-pro-18m';
        $product = Product::where('slug', $slug)->firstOrFail();

        // Check if product is active
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'দুঃখিত! এই প্রোডাক্টটির স্টক বর্তমানে শেষ হয়ে গেছে।',
            ], 422);
        }

        // Check if TrxID was already used
        $existingOrder = Order::where('trx_id', strtoupper(trim($validated['trx_id'])))->first();
        if ($existingOrder) {
            return response()->json([
                'success' => false,
                'message' => 'এই ট্রানজেকশন আইডি (TrxID) দিয়ে ইতোমধ্যে একটি অর্ডার সম্পন্ন হয়েছে!',
            ], 422);
        }

        // Handle screenshot upload
        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
        }

        try {
            $order = DB::transaction(function () use ($validated, $product, $screenshotPath) {
                // 1. Pick and lock the first available single-use link
                $link = DigitalLink::where('product_id', $product->id)
                    ->where('status', 'available')
                    ->orderBy('id', 'asc')
                    ->lockForUpdate()
                    ->first();

                if (!$link) {
                    throw new \Exception('দুঃখিত! আমাদের আজকের স্টক শেষ হয়ে গেছে। অনুগ্রহ করে একটু পরে চেষ্টা করুন বা হোয়াটসঅ্যাপে যোগাযোগ করুন।');
                }

                // 2. Generate unique order number
                $orderNumber = 'DM-' . strtoupper(Str::random(6));

                // 3. Create the order record
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'product_id' => $product->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'amount' => $product->offer_price,
                    'payment_method' => $validated['payment_method'],
                    'sender_phone' => $validated['sender_phone'],
                    'trx_id' => strtoupper(trim($validated['trx_id'])),
                    'screenshot_path' => $screenshotPath,
                    'status' => 'completed',
                    'digital_link_id' => $link->id,
                    'delivered_link' => $link->link_url,
                ]);

                // 4. Mark link as SOLD
                $link->update([
                    'status' => 'sold',
                    'order_id' => $order->id,
                    'delivered_to_phone' => $validated['customer_phone'],
                    'delivered_at' => now(),
                ]);

                return $order;
            });

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'delivered_link' => $order->delivered_link,
                'amount' => $order->amount,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'product_name' => $product->name,
                'message' => 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে! আপনার কাঙ্ক্ষিত সাবস্ক্রিপশন লিংকটি নিচে প্রদর্শিত হলো।',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function trackOrder(Request $request)
    {
        $query = trim($request->input('query'));
        $orders = collect();

        if ($query) {
            $orders = Order::where('customer_phone', 'like', "%{$query}%")
                ->orWhere('trx_id', 'like', "%{$query}%")
                ->orWhere('order_number', 'like', "%{$query}%")
                ->latest()
                ->get();
        }

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('frontend.track', compact('orders', 'query', 'settings'));
    }
}
