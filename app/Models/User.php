<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = ['email', 'customer_id', 'registered_at'];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

}
