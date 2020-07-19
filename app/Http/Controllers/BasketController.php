<?php

    namespace App\Http\Controllers;

    use App\Basket;
    use App\components\Helper;
    use App\Product;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class BasketController extends Controller
    {
        private $data = [
            'error' => false,
            'success' => false,
            'message' => false,
            'html' => false,
            'url' => false,
            'delete' => false,
            'create' => false,
            'data' => false,
        ];

        public function index()
        {
            $basket = Basket::getBasket();
            $this->data['data'] = $basket;
            return response()->json($this->data, 200);
        }

        public function store(Request $request)
        {

        }

        public function show($id)
        {

        }

        public function update(Request $request, $product_id)
        {
            if($product = Product::find($product_id)){
                if(Auth::check()){
                    if(!Basket::existDb($product_id,Auth::user()->id)){
                        Basket::createDB($product,Auth::user()->id);
                        $this->data['success'] = true;
                    }else{
                        $this->data['message'] = 'Товар уже добавлен';
                    }
                }else{
                    if(!Basket::existSession($product_id)){
                        Basket::createSession($product_id);
                        $this->data['success'] = true;
                    }else{
                        $this->data['message'] = 'Товар уже добавлен';
                    }
                }
            }
            $basketItem = Basket::getBasket();
            $this->data['html'] = view('site.ajax.basket-mini', ['basketItem' => $basketItem])->render();
            return response()->json($this->data, 200);
        }

        public function destroy($product_id)
        {
            if($product = Product::find($product_id)){
                if(Auth::check()){
                    if($basket = Basket::existDb($product_id,Auth::user()->id)){
                        $basket->delete();
                        $this->data['delete'] = true;
                    }else{
                        $this->data['message'] = 'Товар не найден в корзине';
                    }
                }else{
                    if(Basket::existSession($product_id)){
                        Basket::deleteSession($product_id);
                        $this->data['delete'] = true;
                    }else{
                        $this->data['message'] = 'Товар не найден в корзине';
                    }
                }
                $basketItem = Basket::getBasket();
                $this->data['html'] = view('site.ajax.basket-mini', ['basketItem' => $basketItem])->render();
                $this->data['success'] = true;
            }else{
                $this->data['message'] = 'Неверный идентификатор товара';
            }
            return response()->json($this->data, 200);
        }
    }
