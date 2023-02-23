<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    use HasFactory;

    protected $table = 'competencies';

    // Don't forget to fill this array
    protected $fillable = [];

    protected $guarded = ['id'];


    public function grades()
    {
        return $this->hasMany(Grade::class, 'id', 'competencyID');
    }
}
