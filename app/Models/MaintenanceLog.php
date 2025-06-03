<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'performed_by',
        'description',
        'maintenance_date',
        'next_due_date',
        'status',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}