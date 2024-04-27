<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User:: class, 'user_id');
    }

    public function artwork() {
        return $this->belongsTo(Artwork:: class, 'artwork_id');
    }
}
