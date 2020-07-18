<?php

namespace App\Http\Controllers;

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
        session(['key' => [
            'yy' => 3,
            'pp' => 4
        ]]);
        session(['key' => [
            'yy' => 10,
            'pp' => 30
        ]]);

        $product = Product::select();
        $product = (new ProductFilter($product,$post))->apply()->paginate(5);

    	return view('site.index', [
    	    'product'  =>  $product,
            'post'  =>  $post,
            'page'  =>  $page,
        ]);
    }
}
