<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'reported_customer_id',
        'feedback',
        'is_bug'
    ];

    protected $casts = [
        'is_bug' => 'boolean'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function reported_customer()
    {
        return $this->belongsTo(User::class, 'reported_customer_id');
    }
}
