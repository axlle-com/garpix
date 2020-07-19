<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Basket extends Model
{
    public const STATUS_WAIT = 0;

    protected $table = 'basket';
    protected $dateFormat = 'U';
    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'quantity',
        'price',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function updateStatus(int $order_id):void
    {
        $this->order_id = $order_id;
        $this->price = $this->product->price;
        $this->save();
    }

    public static function createDB(Product $product, int $user_id):self
    {
        $data['product_id'] = $product->id;
        $data['price'] = $product->price;
        $data['user_id'] = $user_id;
        $data['quantity'] = 1;
        $basket = new static($data);
        $basket->save();
        return $basket;
    }

    public static function existDb(int $product_id, int $user_id)
    {
        return static::where('order_id',static::STATUS_WAIT)
            ->where('user_id',$user_id)
            ->where('product_id',$product_id)
            ->first();
    }

    public static function existSession(int $product_id)
    {
        if(($ids = session('basket', [])) && array_key_exists($product_id,$ids)){
            return true;
        }
        return false;
    }

    public static function deleteSession(int $product_id):void
    {
        $ids = session('basket',[]);
        foreach ($ids as $key => $id){
            if($key == $product_id){
                unset($ids[$key]);
                break;
            }
        }
        session(['basket' => $ids]);
    }

    public static function createSession(int $product_id):void
    {
        $ids = session('basket',[]);
        $product = Product::find($product_id);
        $ids[$product->id] = [
            'title' => $product->title,
            'price' => $product->price,
        ];
        session(['basket' => $ids]);
    }

    public static function emptyUserBasket(int $user_id):void
    {
        $basket = static::where('order_id',static::STATUS_WAIT)
            ->where('user_id',$user_id)
            ->get();
        if(count($basket)){
            foreach ($basket as $item){
                $item->delete();
            }
        }
    }

    public static function toggleType(int $user_id):void
    {
        if($ids = session('basket',[])){
            static::emptyUserBasket($user_id);
            $products = Product::whereIn('id',array_keys($ids))->get();
            if(count($products)){
                foreach ($products as $product){
                    static::createDB($product,$user_id);
                }
            }
        }
        session(['basket' => []]);
    }

    public static function getBasket():array
    {
        $array = [];
        if(Auth::check()){
            $basket = Basket::with('product')
                ->where('user_id',Auth::user()->id)
                ->where('order_id',static::STATUS_WAIT)
                ->get();
            if (count($basket)){
                foreach ($basket as $item){
                    $array[$item->product->id]['title'] = $item->product->title;
                    $array[$item->product->id]['price'] = $item->product->price;
                }
            }
        }else{
            $basket = session('basket',[]);
            if (count($basket)){
                foreach ($basket as $key => $item){
                    $array[$key]['title'] = $item['title'];
                    $array[$key]['price'] = $item['price'];
                }
            }
        }
        return $array;

    }

}
