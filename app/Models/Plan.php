<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'price', 'description'];

    protected $appends = ['price_br'];

    public function getPriceBrAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }
}
