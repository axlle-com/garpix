<?php

namespace App\Http\Controllers;

use App\Basket;
use App\components\Helper;
use App\components\ProductFilter;
use App\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $post = Helper::clearArray($request->all());
        $page = null;
        if(isset($post['page'])){
            $page = $post['page'];
            unset($post['page']);
        }

        $product = Product::select();
        $product = (new ProductFilter($product,$post))->apply()->paginate(5);

        $basket = Basket::getBasket();
    	return view('site.index', [
    	    'product'  =>  $product,
            'post'  =>  $post,
            'page'  =>  $page,
            'basket'  =>  $basket,
        ]);
    }
}
