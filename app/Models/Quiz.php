<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id')->inRandomOrder();
    }
}
