<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = [
        'title',
        'description',
        'price',
    ];

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }

}
