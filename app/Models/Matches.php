<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    //
    protected $fillable = ['city1_id', 'city2_id',];

    // public function city1()
    // {
    //     return $this->belongsTo(City::class, 'city1_id');
    // }

    // public function city2()
    // {
    //     return $this->belongsTo(City::class, 'city2_id');
    // }

    public function city1()
{
    return $this->belongsTo(City::class, 'city1_id');
}

public function city2()
{
    return $this->belongsTo(City::class, 'city2_id');
}
}
