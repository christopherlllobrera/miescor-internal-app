<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\Email\Concerns\InteractsWithEmailAuthentication;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanResetPassword, FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasAvatar, HasEmailAuthentication
{
    use CanResetPasswordTrait;
    use HasFactory;
    use HasPermissions;
    use HasRoles;
    use InteractsWithAppAuthentication;
    use InteractsWithAppAuthenticationRecovery;
    use InteractsWithEmailAuthentication;
    use LogsActivity;
    use Notifiable;

    protected $fillable = [
        'empNo',
        'username',
        'comp_email',
        'password',
        'password_expires_at',
        'access_level',
        'otp_expires_at',
        'reg_otp',
        'reg_otp_expires_at',
        'is_locked',
        'cookies_validation',
        'cookies_validation_count',
        'session_id',
        'first_login',
        'avatar_url',
        'app_authentication_secret',
        'has_email_authentication',
        'app_authentication_recovery_codes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'app_authentication_secret',
        'app_authentication_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_expires_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'reg_otp_expires_at' => 'datetime',
        'has_email_authentication' => 'boolean',
        'first_login' => 'boolean',
        'password' => 'hashed',
        'app_authentication_secret' => 'encrypted',
        'app_authentication_recovery_codes' => 'encrypted:array',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('User')
            ->setDescriptionForEvent(fn (string $event) => "User has been {$event}")
            ->logAll()
            ->logOnlyDirty()
            // ->dontSubmitEmptyLogs()
            ;
    }

    public function routeNotificationForMail()
    {
        return $this->comp_email;
    }

    public function getEmailForPasswordReset(): string
    {
        return $this->comp_email;
    }

    public function getNameAttribute()
    {
        return $this->username;
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'post_like')->withTimestamps();
    }

    public function hasLiked(Post $post)
    {
        return $this->likes()->where('post_id', $post->id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'EmpNo', 'empNo');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    public function getFilamentEmailAuthenticationAddress(): string
    {
        return $this->comp_email;
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->comp_email,
            set: fn ($value) => ['comp_email' => $value],
        );
    }
}
