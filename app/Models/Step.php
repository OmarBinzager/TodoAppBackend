<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    //
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'step',
        'step_index',
        'task_id',
    ];
    function task_id()
    {
        return $this->belongsTo(Task::class);
    }
}