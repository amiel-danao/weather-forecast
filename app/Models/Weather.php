<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    use HasFactory;

    protected $tale = "weather";
    protected $guarded = "";
    // protected $casts = [ 'day' => 'datetime'];

    protected $fillable = [
        'year',
        'month',
        'day',
        'rainfall',
        'temperature_min',
        'temperature_max',
        'temperature_mean',
        'wind_speed',
        'wind_direction'
    ];
}
