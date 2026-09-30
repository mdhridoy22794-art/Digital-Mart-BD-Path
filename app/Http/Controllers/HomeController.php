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
            'quantity' => 'nullable|integer|min:1|max:100',
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

        $quantity = (int) ($validated['quantity'] ?? 1);
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Check current available unsold stock
        $availableStock = DigitalLink::where('product_id', $product->id)
            ->where('status', 'available')
            ->count();

        if ($availableStock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => "দুঃখিত! আপনি {$quantity}টি লিংক অর্ডার করতে চেয়েছেন, কিন্তু স্টকে বর্তমানে {$availableStock}টি লিংক অবশিষ্ট রয়েছে।",
            ], 422);
        }

        // Check if TrxID was already used
        $cleanTrxId = strtoupper(trim($validated['trx_id']));
        $existingOrder = Order::where('trx_id', $cleanTrxId)->first();
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

        $totalAmount = $product->offer_price * $quantity;

        try {
            $result = DB::transaction(function () use ($validated, $product, $quantity, $totalAmount, $cleanTrxId, $screenshotPath) {
                // 1. Pick and lock the links in strict serial FIFO order (id ASC)
                $links = DigitalLink::where('product_id', $product->id)
                    ->where('status', 'available')
                    ->orderBy('id', 'asc')
                    ->lockForUpdate()
                    ->take($quantity)
                    ->get();

                if ($links->count() < $quantity) {
                    throw new \Exception('দুঃখিত! পর্যাপ্ত লিংক স্টকে নেই। অনুগ্রহ করে একটু পর চেষ্টা করুন।');
                }

                // 2. Generate unique order number
                $orderNumber = 'DM-' . strtoupper(Str::random(6));

                // 3. Newline separated links
                $deliveredLinksText = $links->pluck('link_url')->implode("\n");
                $firstLinkId = $links->first()->id;

                // 4. Create the order record
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'product_id' => $product->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'quantity' => $quantity,
                    'amount' => $totalAmount,
                    'payment_method' => $validated['payment_method'],
                    'sender_phone' => $validated['sender_phone'],
                    'trx_id' => $cleanTrxId,
                    'screenshot_path' => $screenshotPath,
                    'status' => 'completed',
                    'digital_link_id' => $firstLinkId,
                    'delivered_link' => $deliveredLinksText,
                ]);

                // 5. Mark each link as SOLD in serial order
                foreach ($links as $link) {
                    $link->update([
                        'status' => 'sold',
                        'order_id' => $order->id,
                        'delivered_to_phone' => $validated['customer_phone'],
                        'delivered_at' => now(),
                    ]);
                }

                return ['order' => $order, 'links' => $links];
            });

            $orderRecord = $result['order'];
            $orderedLinks = $result['links'];

            $linksPayload = $orderedLinks->values()->map(function ($item, $index) {
                return [
                    'serial' => $index + 1,
                    'id' => $item->id,
                    'url' => $item->link_url,
                ];
            })->toArray();

            return response()->json([
                'success' => true,
                'order_number' => $orderRecord->order_number,
                'quantity' => $orderRecord->quantity,
                'delivered_link' => $orderRecord->delivered_link,
                'delivered_links' => $linksPayload,
                'amount' => $orderRecord->amount,
                'customer_name' => $orderRecord->customer_name,
                'customer_phone' => $orderRecord->customer_phone,
                'product_name' => $product->name,
                'message' => 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে! আপনার কাঙ্ক্ষিত সাবস্ক্রিপশন লিংকগুলো নিচে ক্রমানুসারে প্রদর্শিত হলো।',
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

    public function orderSuccess($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with(['product', 'digitalLinks'])->firstOrFail();
        $settings = Setting::pluck('value', 'key')->toArray();

        $deliveredLinks = [];
        if (!empty($order->delivered_link)) {
            $urls = preg_split('/\r\n|\r|\n/', trim($order->delivered_link));
            foreach ($urls as $idx => $url) {
                $u = trim($url);
                if (!empty($u)) {
                    $deliveredLinks[] = [
                        'serial' => $idx + 1,
                        'url' => $u,
                    ];
                }
            }
        }

        return view('frontend.order_success', compact('order', 'deliveredLinks', 'settings'));
    }
}
