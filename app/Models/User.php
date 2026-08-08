<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use NunoMazer\Samehouse\BelongsToTenants;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token', 'root'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, BelongsToTenants;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var string[]
     */
    protected array $tenantColumns = ['site_id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Used in token generation (which I'm not sure we're still doing)
     * Currently, If you have a login you are considered an admin
     *
     * @return Attribute
     */
    protected function admin() : Attribute
    {
        return Attribute::make(
            get: fn () => true,
        );
    }
}
