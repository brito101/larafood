<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanDetail extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'plan_id'];


    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
