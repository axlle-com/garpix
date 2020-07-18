<?php

namespace App\Http\Controllers;

use App\Basket;
use App\components\Helper;
use App\components\ProductFilter;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AjaxController extends Controller
{
    private $data = [
        'error' => false,
        'success' => false,
        'message' => false,
        'html' => false,
        'url' => false,
    ];

    public function sort(Request $request)
    {
        $post = Helper::clearArray($request->all());

        $string = Helper::queryString($post);
        if(isset($post['page'])){
            unset($post['page']);
        }

        $product = Product::select();
        $product = (new ProductFilter($product,$post))->apply()->paginate(5)->setPath('/');

        $this->data['url'] = '/' . ($string ? '?'.$string : '');
        $this->data['html'] = view('site.index', ['product' => $product, 'post' => $post])->render();
        $this->data['success'] = true;

        return json_encode($this->data);
    }

    public function basket($product_id)
    {
        $product_id = (int)$product_id;
        if($product = Product::find($product_id)){
            if(Auth::check()){
                $basket = Basket::existOrCreateDb($product,Auth::user()->id);
            }else{
                $basket = Basket::existOrCreateSession($product);
            }
//            $this->data['html'] = view('ajax.basket_mini', ['basket' => $basket])->render();
            $this->data['success'] = true;
        }else{
            $this->data['message'] = 'Неверный идентификатор товара';
        }

        return json_encode($this->data);
    }

}
