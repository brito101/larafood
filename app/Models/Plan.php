<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'url', 'price', 'description'];

    protected $appends = ['price_br'];

    public function getPriceBrAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function details()
    {
        return $this->hasMany(PlanDetail::class);
    }

    public function profiles(){
        return $this->hasMany(PlanProfile::class);
    }

    public function tenants(){
        return $this->hasMany(Tenant::class);
    }
}
