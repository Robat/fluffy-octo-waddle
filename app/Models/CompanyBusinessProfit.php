<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBusinessProfit extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'range'];
}
