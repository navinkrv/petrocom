<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientDetail extends Model
{
    use HasFactory;

    protected $table = "client_details";
    protected $primaryKey = "id";

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'client_id', 'id');
    }

    public function job(): HasMany
    {
        return $this->hasMany(Job::class, 'client_id', 'id');

    }
}
