<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getDocumentIdAttribute()
    {
        return $this->attributes['tax_id'] ?? null;
    }

    public function setDocumentIdAttribute($value)
    {
        $this->attributes['tax_id'] = $value;
    }
}
