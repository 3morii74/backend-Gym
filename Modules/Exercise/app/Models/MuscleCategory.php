<?php

namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MuscleCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    public function muscles()
    {
        return $this->hasMany(Muscle::class, 'muscle_category_id');
    }
}
