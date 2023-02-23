<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySpecialBonus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $guarded = ['id'];


    public function details()
    {
        return $this->hasMany(CompanySpecialBonusDetail::class, 'special_id', 'id');
    }
}
