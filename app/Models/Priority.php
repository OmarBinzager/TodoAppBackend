<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    public $timestamps = false;
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'color',
        'user_id',
    ];
    public function user_id()
    {
        return $this->belongsTo(User::class);
    }
}