<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\Uri\Builder;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'stock_qtt',
        'barcode',
        'family_code',
    ];

    public function family()
    {
        return $this->belongsTo(ProductFamily::class, 'family_code', 'code');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
