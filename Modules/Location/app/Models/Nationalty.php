<?php

namespace Modules\Location\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Location\Database\Factories\NationaltyFactory;

class Nationality extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = ['status', 'name'];


    public function countryOne()
    {
        return $this->hasOne(Country::class, 'nationality_id', 'id')->where('status', 'active');;
    }
}
