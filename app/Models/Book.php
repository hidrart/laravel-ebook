<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'order_id',
        'cart_id'
    ];

    protected $load = ['order', 'cart'];


    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function cart() {
        return $this->belongsTo(Cart::class);
    }

    public function scopeFilter($query, array $filter) {    
        $query->when($filter['search'] ?? false, function($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('author', 'like', '%' . $search . '%');
        });
        return $query;
    }
}
