<?php

namespace NagSamayam\AdminFortify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use NagSamayam\AdminFortify\Database\Factories\AdminFactory;
use NagSamayam\AdminFortify\TwoFactorAuthenticatable;

class Admin extends Model
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    public const TABLE_NAME = 'admins';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'notify_on_login' => 'boolean',
    ];

    protected $dates = [
        'otp_sent_at',
        'two_factor_enabled_at',
        'banned_at',
    ];

    protected static function newFactory()
    {
        return AdminFactory::new();
    }

    public function logins(): HasMany
    {
        return $this->hasMany(AdminLoginLog::class, 'admin_id');
    }

    public function lastLogin(): BelongsTo
    {
        return $this->belongsTo(AdminLoginLog::class, 'admin_id');
    }

    public function setFullNameAttribute(string $fullName)
    {
        $this->attributes['full_name'] = ucwords(strtolower(trim($fullName)));
    }

    public function setEmailAttribute(string $email): void
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getOtp(): ?string
    {
        return decrypt($this->otp);
    }

    public function is2faEnabled(): bool
    {
        return ! is_null($this->two_factor_enabled_at);
    }

    public function enabledNofifyOnLogin(): bool
    {
        return $this->notify_on_login;
    }

    public function isBanned(): bool
    {
        return ! is_null($this->banned_at);
    }

    public function isBlocked(): bool
    {
        return $this->isBanned();
    }

    public function clearOtp(): self
    {
        return tap($this)->update([
            'otp' => null,
            'otp_sent_at' => null,
        ]);
    }

    public function banAdmin(): self
    {
        return tap($this)->update([
            'banned_at' => now(),
        ]);
    }

    public function unbanAdmin(): self
    {
        return tap($this)->update([
            'banned_at' => null,
        ]);
    }
}
