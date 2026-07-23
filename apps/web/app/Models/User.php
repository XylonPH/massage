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
    'username', 'display_name', 'profile_biography', 'pronoun_text',
    'gender_identity', 'type_handedness', 'visibility_scope',
    'account_preference', 'appearance_preference', 'notification_preference',
    'privacy_preference', 'booking_preference', 'email', 'password', 'birth_date',
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
            $user->visibility_scope ??= 'PRV';
            $user->type_handedness ??= 'UN';
            $user->appearance_preference ??= [
                'color_mode' => 'SYS',
                'text_scale_percent' => 100,
                'is_high_contrast' => false,
                'is_reduced_motion' => false,
            ];
            $user->notification_preference ??= [
                'notification_channel_list' => ['WEB', 'EML'],
                'notification_category_list' => ['SEC'],
                'digest_frequency' => 'IMM',
                'is_marketing_email_opt_in' => false,
            ];
            $user->privacy_preference ??= [
                'visibility_activity' => 'PRV',
                'visibility_gender' => 'PRV',
                'visibility_handedness' => 'PRV',
                'type_birth_date_display' => 'HID',
                'is_social_connection_public' => false,
                'is_premium_label_public' => false,
                'is_analytics_cookie_allowed' => false,
                'is_personalization_cookie_allowed' => false,
                'is_marketing_cookie_allowed' => false,
            ];
            $user->revision_number ??= 1;
        });

        static::saving(function (self $user): void {
            foreach (['display_name', 'profile_biography', 'pronoun_text', 'gender_identity'] as $field) {
                if (! filled($user->getAttribute($field))) {
                    $user->unset($field);
                }
            }

            foreach (['account_preference', 'booking_preference'] as $field) {
                if ($user->getAttribute($field) === []) {
                    $user->unset($field);
                }
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'terms_accepted_at' => 'datetime',
            'privacy_acknowledged_at' => 'datetime',
            'is_marketing_email_opt_in' => 'boolean',
            'account_preference' => 'array',
            'appearance_preference' => 'array',
            'notification_preference' => 'array',
            'privacy_preference' => 'array',
            'booking_preference' => 'array',
            'password' => 'hashed',
        ];
    }

    public function publicName(): string
    {
        return $this->display_name ?: $this->username;
    }

    public function profileRouteKey(): string
    {
        return $this->username;
    }

    public function isActive(): bool
    {
        return $this->status_account === 'ACT' && $this->status_membership === 'ACT';
    }

    public function accountStatusLabel(): string
    {
        return match ($this->status_account) {
            'PND' => 'Pending Verification',
            'ACT' => 'Active',
            'INA' => 'Inactive',
            'SUS' => 'Suspended',
            'BAN' => 'Banned',
            'DEL' => 'Deleted',
            'TST' => 'Test Account',
            default => $this->status_account ?? 'Unknown',
        };
    }

    public function membershipStatusLabel(): string
    {
        return match ($this->status_membership) {
            'PEL' => 'Pending Eligibility',
            'ACT' => 'Active',
            'RST' => 'Restricted',
            'END' => 'Ended',
            default => $this->status_membership ?? 'Unknown',
        };
    }

    public function accountStatusBadgeClass(): string
    {
        return match ($this->status_account) {
            'ACT' => 'bg-leaf-100 text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300',
            'PND' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
            'SUS', 'BAN', 'DEL' => 'bg-ember-100 text-ember-800 dark:bg-ember-950 dark:text-ember-300',
            default => 'bg-ink-100 text-ink-700 dark:bg-ink-800 dark:text-ink-300',
        };
    }

    public function membershipStatusBadgeClass(): string
    {
        return match ($this->status_membership) {
            'ACT' => 'bg-leaf-100 text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300',
            'PEL' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
            'RST', 'END' => 'bg-ember-100 text-ember-800 dark:bg-ember-950 dark:text-ember-300',
            default => 'bg-ink-100 text-ink-700 dark:bg-ink-800 dark:text-ink-300',
        };
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
