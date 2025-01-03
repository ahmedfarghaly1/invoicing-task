<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = ['user_id', 'activated_at', 'appointment_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
