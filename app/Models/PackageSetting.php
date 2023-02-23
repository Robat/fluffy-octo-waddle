<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageSetting extends Model
{
    use HasFactory;
    protected $table = 'package_settings';

    protected $appends = ['all_packages'];

    public function getAllPackagesAttribute()
    {
        return count(json_decode($this->modules, true)) >= 20 ? true : false;
    }
}
