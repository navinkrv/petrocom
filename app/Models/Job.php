<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    protected $table = "jobs";
    protected $primaryKey = "id";
    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientDetail::class, "client_id", "id");
    }
    protected $casts = [
        'job_location_data' => 'array',
        'invoice' => 'array',
        'update' => 'array'
    ];
}
