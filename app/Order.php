<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

}
