<?php

namespace NagSamayam\AdminFortify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLoginLog extends Model
{
    use HasFactory;

    protected $table = 'admin_login_log';

    public $timestamps = false;

    protected $casts = [
        'login_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip_address;
    }

    public function getUserAgent(): ?string
    {
        return $this->user_agent;
    }
}
