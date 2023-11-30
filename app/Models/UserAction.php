<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_id', 'action', 'consent_id', 'bitacora_date'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
