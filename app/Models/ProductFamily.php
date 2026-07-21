<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFamily extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFamilyFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];


    public function products()
    {
        return $this->hasMany(Product::class, 'family_code', 'code');
    }
}
