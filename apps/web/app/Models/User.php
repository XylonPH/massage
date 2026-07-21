<?php

namespace App\Models;

use App\Notifications\PasswordResetLink;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use MongoDB\Laravel\Auth\User as MongoAuthenticatable;

/**
 * The account, credential, contact, and membership record boundaries here
 * follow docs/07-accounts/account-and-authentication-system.txt
 * section 18 (Conceptual Record Responsibilities). These stay embedded in
 * one collection for the initial implementation, per that document's
 * guidance to avoid premature collection splitting.
 */
#[Fillable([
    'username', 'display_name', 'bio', 'email', 'password', 'birth_date',
    'terms_accepted_at', 'terms_accepted_version',
    'privacy_acknowledged_at', 'privacy_acknowledged_version',
    'is_marketing_email_opt_in',
])]
#[Hidden(['password', 'remember_token'])]
class User extends MongoAuthenticatable implements MustVerifyEmailContract
{
    use HasFactory, MustVerifyEmail, Notifiable;

    protected $connection = 'mongodb';

    protected $table = 'user_main';

    protected $primaryKey = '_id';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $user): void {
            if (! $user->getKey()) {
                $user->{$user->getKeyName()} = Str::random(16);
            }

            $user->status_account ??= 'PND';
            $user->status_membership ??= 'PEL';
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
            'privacy_acknowledged_at' => 'datetime',
            'is_marketing_email_opt_in' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function publicName(): string
    {
        return $this->display_name ?: $this->username;
    }

    public function isActive(): bool
    {
        return $this->status_account === 'ACT' && $this->status_membership === 'ACT';
    }

    public function sendPasswordResetNotification($token): void
    {
        // Recovery is available only through an already verified email.
        // Keep the controller response neutral for pending accounts while
        // ensuring no usable recovery message is delivered to them.
        if (! $this->hasVerifiedEmail()) {
            return;
        }

        $this->notify(new PasswordResetLink($token));
    }
}
