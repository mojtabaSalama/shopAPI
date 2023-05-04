<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin_country extends Model
{
    use HasFactory;

    public function admin()
    {
        return $this->belongsTo(admin::class);
    }
}
