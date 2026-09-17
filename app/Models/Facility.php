<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'description', 'image_url'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
