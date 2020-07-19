<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    protected $table = 'order';
    protected $dateFormat = 'U';
    protected $fillable = [
        'user_id',
    ];

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }

    public static function create(array $data):self
    {
        $order = new static($data);
        $order->save();
        $basket = Basket::where('order_id',Basket::STATUS_WAIT)
            ->where('user_id',Auth::user()->id)
            ->get();
        foreach ($basket as $item){
            $item->updateStatus($order->id);
        }
        return $order;
    }

}
