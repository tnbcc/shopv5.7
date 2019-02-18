<?php

namespace App\Models;

use App\Exceptions\InvalidRequestException;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //减库存
    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            throw new InvalidRequestException('键库存不可小于0');
        }

        return $this->newQuery()->where('id', $this->id)->where('stock', '>=', $amount)->decrement('stock', $amount);
    }

    //加库存
    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InvalidRequestException('加库存不可小于0');
        }

        return $this->increment('stock', $amount);
    }
}
