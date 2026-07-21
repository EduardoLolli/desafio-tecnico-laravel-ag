<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function family(): BelongsTo
    {
        return $this->belongsTo(ProductFamily::class, 'family_code', 'code');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($q, $name) {
            $q->where('name', 'like', "%{$name}%");
        });

        $query->when($filters['min_price'] ?? null, function ($q, $price) {
            $q->where('price', '>=', $price);
        });

        $query->when($filters['max_price'] ?? null, function ($q, $price) {
            $q->where('price', '<=', $price);
        });

        $query->when($filters['min_qtt'] ?? null, function ($q, $qtt) {
            $q->where('stock_qtt', '>=', $qtt);
        });

        $query->when($filters['max_qtt'] ?? null, function ($q, $qtt) {
            $q->where('stock_qtt', '<=', $qtt);
        });

        $query->when($filters['family'] ?? null, function ($q, $family) {
            $q->whereHas('family', function ($qFamily) use ($family) {
                $qFamily->where('code', $family)
                    ->orWhere('name', 'like', "%{$family}%");
            });
        });
    }
}
