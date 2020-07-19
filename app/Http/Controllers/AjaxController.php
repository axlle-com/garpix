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
        'delete' => false,
        'create' => false,
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
        $basket = Basket::getBasket();
        $this->data['url'] = '/' . ($string ? '?'.$string : '');
        $this->data['html'] = view('site.index', [
            'product' => $product,
            'post' => $post,
            'basket' => $basket,
        ])->render();
        $this->data['success'] = true;

        return json_encode($this->data);
    }

    public function basket($product_id)
    {
        if($product = Product::find($product_id)){
            if(Auth::check()){
                if($basket = Basket::existDb($product_id,Auth::user()->id)){
                    $basket->delete();
                    $this->data['delete'] = true;
                }else{
                    Basket::createDB($product,Auth::user()->id);
                    $this->data['create'] = true;
                }
            }else{
                if(Basket::existSession($product_id)){
                    Basket::deleteSession($product_id);
                    $this->data['delete'] = true;
                }else{
                    Basket::createSession($product_id);
                    $this->data['create'] = true;
                }
            }
            $basketItem = Basket::getBasket();
            $this->data['html'] = view('site.ajax.basket-mini', ['basketItem' => $basketItem])->render();
            $this->data['success'] = true;
        }else{
            $this->data['message'] = 'Неверный идентификатор товара';
        }

        return json_encode($this->data);
    }

    public function orderBasket($product_id)
    {
        $product_id = (int)$product_id;

        if($product = Product::find($product_id)){
            if(Auth::check()){
                if($basketItem = Basket::existDb($product_id,Auth::user()->id)){
                    $basketItem->delete();
                    $basket = Basket::getBasket();
                    $this->data['html'] = view('site.ajax.basket', ['basket' => $basket])->render();
                    $this->data['success'] = true;
                }else{
                    $this->data['message'] = 'Корзина не найдена';
                }
            }else{
                $this->data['message'] = 'Необходимо авторизоваться';
            }
        }else{
            $this->data['message'] = 'Неверный идентификатор товара';
        }

        return json_encode($this->data);
    }

}
