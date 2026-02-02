<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    use HasFactory;

    // To'ldirish mumkin bo'lgan ustunlar
    protected $fillable = [
        'track_code',
        'is_anonymous',
        'full_name',
        'phone',
        'type',
        'region',
        'district',
        'organization',
        'description',
        'files',
        'status',
        'admin_note',
        'assigned_to' // <-- BU BO'LISHI SHART
    ];

    // Ma'lumot turlari
    protected $casts = [
        'files' => 'array',
        'is_anonymous' => 'boolean',
    ];

    // --- BOG'LANISHLAR (RELATIONSHIPS) ---

    // Arizaga biriktirilgan xodimni olish funksiyasi
    public function assignedUser()
    {
        // 'assigned_to' ustuni orqali User modeliga bog'lanamiz
        return $this->belongsTo(User::class, 'assigned_to');
    }
}