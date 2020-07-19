<?php

namespace App\Http\Controllers;

use App\Basket;
use App\components\Helper;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $post = Helper::clearArray($request->all());
        $orders = Order::with('baskets')->where('user_id',Auth::user()->id)->get();
        $basket = Basket::getBasket();
    	return view('site.order', [
    	    'basket'  =>  $basket,
            'post'  =>  $post,
            'orders' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $post = Helper::clearArray($request->all());
        $order = Order::create(['user_id' => Auth::user()->id]);
        $orders = Order::with('baskets')->where('user_id',Auth::user()->id)->get();
        return redirect()->route('order')->with(['orders' => $orders]);
    }
}
