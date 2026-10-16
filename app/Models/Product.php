<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!empty($this->attributes['image'])) {
            return asset('img/products/' . $this->attributes['image']);
        }

        $skuPrefix = substr($this->sku, 0, 7);
        $skuImageMap = [
            'HID-TOM' => 'tomate.jpg',
            'HID-LEC' => 'lechuga.jpg',
            'HID-CIL' => 'cilantro.jpg',
            'HID-API' => 'apio.jpg',
            'HID-ACE' => 'acelga.jpg',
        ];

        $name = strtolower($this->name);
        if (isset($skuImageMap[$skuPrefix])) {
            return asset('img/products/' . $skuImageMap[$skuPrefix]);
        } elseif (str_contains($name, 'toma')) {
            return asset('img/products/tomate.jpg');
        } elseif (str_contains($name, 'lechu')) {
            return asset('img/products/lechuga.jpg');
        } elseif (str_contains($name, 'cilan')) {
            return asset('img/products/cilantro.jpg');
        } elseif (str_contains($name, 'apio')) {
            return asset('img/products/apio.jpg');
        } elseif (str_contains($name, 'acel')) {
            return asset('img/products/acelga.jpg');
        }

        return null;
    }

    public function stockIntakes()
    {
        return $this->hasMany(StockIntake::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
