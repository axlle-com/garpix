<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

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

    public static function createDB(array $data):self
    {
        $basket = new static($data);
        $basket->save();
        return $basket;
    }

    public function updateDb():void
    {
        $this->quantity++;
        $this->save();
    }

    public static function existOrCreateDb(Product $product, int $user_id)
    {
        $basket = static::where('order_id',static::STATUS_WAIT)
            ->where('user_id',$user_id)
            ->where('product_id',$product->id)
            ->first();
        if($basket){
            $basket->updateDb();
        }else{
            $data['product_id'] = $product->id;
            $data['price'] = $product->price;
            $data['user_id'] = $user_id;
            $data['quantity'] = 1;
            $basket = static::createDB($data);
        }
        return static::with('product')
            ->where('order_id',static::STATUS_WAIT)
            ->where('user_id',$user_id)
            ->get();
    }

    public static function existOrCreateSession(int $product_id)
    {

    }

    public static function updateSession(int $id)
    {

    }

    public static function createSession(int $id)
    {

    }

}
