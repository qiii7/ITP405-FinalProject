<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function comments() {
        return $this->hasMany(Comment:: class, 'artwork_id', 'id');
    }

}
