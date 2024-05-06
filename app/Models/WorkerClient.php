<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerClient extends Model
{
    use HasFactory;

    protected $table = 'worker_clients';

    protected $fillable = ['user_id', 'worker_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class);
    }

}
