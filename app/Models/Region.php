<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    //
    protected $fillable = [
        'name',
        'description'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
