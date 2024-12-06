<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $fillable = ['name', 'region_id', 'region', 'matches'];

    // public function region()
    // {
    //     return $this->belongsTo(Region::class);
    // }

    // public function hostMatches()
    // {
    //     return $this->hasMany(Matches::class, 'city1_id')
    //         ->orWhere('city2_id', $this->id);
    // }

    public function region()
{
    return $this->belongsTo(Region::class);
}

public function matches()
{
    return $this->hasMany(Matches::class, 'city1_id')->orWhere('city2_id', $this->id);
}
}
