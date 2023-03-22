<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SascarPosition extends Model
{
    use HasFactory;

    protected $table = 'sascar_positions';
    protected $fillable = ['placa', 'latitude', 'longitude'];
}
