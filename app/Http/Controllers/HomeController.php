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
        return response()->json([
            'success' => false,
            'message' => 'সরাসরি ম্যানুয়াল অর্ডার গ্রহণ বন্ধ রয়েছে। অনুগ্রহ করে পেমেন্ট গেটওয়ের মাধ্যমে অর্ডার সম্পন্ন করুন।',
        ], 403);
    }

    public function trackOrder(Request $request)
    {
        $query = trim($request->input('query'));
        $orders = collect();

        if (!empty($query)) {
            $orders = Order::where(function ($q) use ($query) {
                $q->where('customer_phone', $query)
                  ->orWhere('trx_id', $query)
                  ->orWhere('trx_id', strtoupper($query))
                  ->orWhere('order_number', $query)
                  ->orWhere('order_number', 'DM-' . strtoupper($query))
                  ->orWhere('order_number', '#' . $query);
            })
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
