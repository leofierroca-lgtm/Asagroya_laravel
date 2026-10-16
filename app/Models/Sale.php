<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getUserIdAttribute()
    {
        return $this->attributes['seller_id'] ?? null;
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['seller_id'] = $value;
    }
}
