<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'designations';
    protected $guarded = ['id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    public function members()
    {
        return $this->hasMany(Employee::class, 'designation_id');
    }
}
