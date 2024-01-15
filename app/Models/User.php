<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements HasTenants, FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'email',
        'password',
        'status',
        //'is_admin',
        'position_id',
        'notes',
        'address',
        'w2w4_path',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function restaurants():BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class);
    }

    public function restaurant(): BelongsToMany
    {
        return $this->restaurants();
    }

    public function report(): BelongsToMany
    {
        return $this->belongsToMany(Report::class);
    }

    public function position(){
        return $this->belongsTo(Position::class);
    }


    public function canAccessTenant(Model $tenant): bool
    {
        return $this->restaurants->contains($tenant);
    }
    public function getTenants(Panel $panel): Collection
    {
        return $this->restaurants;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // $this->hasRole(['Owner','Admin','Manager']) && $this->hasVerifiedEmail();
        //return true;
        return match($panel->getId()) {
            'admin' => $this->hasRole(['Admin','Manager','Owner', 'Assistant Manager','Kitchen Manager']),
            'static' => $this->hasRole(['Admin','Manager','Owner']),
            'employee' => $this->hasRole(['Admin','Manager','Owner','Employee']),
            'owner' => $this->hasRole(['Admin','Owner','Consultant','Accountant']),
        };


    }




}
