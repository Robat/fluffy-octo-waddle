<?php

namespace App\Models;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleSetting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    // public static function checkModule($moduleName) {
    //     $admin = auth()->guard('admin')->user();

    //     $module = ModuleSetting::where('module_name', $moduleName);


    //     $module = $module->where('type', 'admin');
    //     $module = $module->where('status', 'active');

    //     $module = $module->first();
    //     if($module){
    //         if($module->status == 'active'){
    //             return true;
    //         }
    //     }

    //     return false;
    // }
}
