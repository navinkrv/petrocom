<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = "jobs";
    protected $primaryKey = "id";

    protected $casts = [
        'job_location_data' => 'array',
        'invoice' => 'array',
        'update' => 'array'
    ];
}
