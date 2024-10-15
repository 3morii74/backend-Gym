<?php

namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuscleCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function muscles()
    {
        return $this->hasMany(Muscle::class);
    }
}
