<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasRoles, Sortable,  HasApiTokens, BaseModel, HasFactory;

    public $sortable = [
        'id',
        'name',
        'email',
        'active',
        'username',
        'agent_id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'username', 'agent_id', 'late_online', 'dob', 'address', 'phone', 'remember_token'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function profile()
    {
        return $this->hasOne('App\Models\UserProfiles');
    }


    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', Config("settings.active"));
    }

    public function scopeCustomer($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', config('settings.roles.customer'));
        });
    }

    /**
     * Show avatar
     * @return string|void
     */
    public function showAvatar($attrs = null, $default = "")
    {
        if (isset($this->profile) && !empty($this->profile->avatar)) {
            $avatar = $this->profile->avatar;
            if (\Storage::exists($avatar))
                return '<img alt="avatar" width=40px; src="' . asset(\Storage::url($avatar)) . '" />';
        }
        if (!empty($default)) return '<img alt="avatar" class="' . $attrs["class"] . '" src="' . $default . '" />';
        return;
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->where('active', Config("settings.active"))->first();
    }

    /**
     * Token api
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany('Laravel\Passport\Token');
    }

    /**
     * Revoke tokens - this user
     */
    public function revokeAllTokens()
    {
        $tokens = $this->tokens()->where('revoked', 0)->get();
        foreach ($tokens as $token) {
            $token->revoke();
        }
    }

    public function deviceNotifies()
    {
        return $this->hasMany('App\DeviceNotify');
    }

    public function roleBelongToCompany()
    {
        return $this->hasRole(config('settings.roles.company_admin'))
            || $this->hasRole(config('company_employee.roles.company_employee'))
            || $this->hasRole(config('settings.roles.company_booking'));
    }

    public function roleBelongToAgent()
    {
        return $this->hasRole(config('settings.roles.agent_admin')) || $this->hasRole(config('settings.roles.agent_employee'));
    }

    public function roleBelongToCustomer()
    {
        return $this->hasRole(config('settings.roles.customer'));
    }

    public function isAdminCompany()
    {
        return $this->hasRole(config('settings.roles.company_admin'));
    }

    public function isAdminAgent()
    {
        return $this->hasRole(config('settings.roles.agent_admin'));
    }

    public function isCompanyManagerBooking()
    {
        return $this->hasRole(config('settings.roles.company_booking'));
    }


    public function isEmployeeCompany()
    {
        return $this->hasRole(config('settings.roles.company_employee'));
    }

    public function isEmployeeAgent()
    {
        return $this->hasRole(config('settings.roles.agent_employee'));
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            static::bootCreatingByRole($user);
        });

        static::updated(function ($user) {
            //when inactive user, revoke all tokens of this user
            // if ($user->active === \Config::get("settings.inactive"))
            //     $user->revokeAllTokens();
        });

        static::saving(function ($user) {

        });
        // static::deleted(function ($user) {
        //     $user->revokeAllTokens();
        //     //check xóa hoàn toàn trong CSDL
        //     if ($user->isForceDeleting()) {
        //         $profile = $user->profile;
        //         if (!empty($profile->avatar)) {
        //             \Storage::delete($profile->avatar);
        //         }
        //     }
        // });
    }

    /**
     * Users belong to my company (not agent)
     * @return mixed
     */
    public static function getUsersByOnlyCompany()
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', [config('settings.roles.company_admin'), config('settings.roles.company_employee'), config('settings.roles.company_booking')]);
        })->pluck('name', 'id');
    }

    /**
     * Users belong to my agent
     * @return mixed
     */
    public static function getUsersByOnlyAgent()
    {
        return User::where('agent_id', '=', \Auth::user()->agent_id)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', [config('settings.roles.agent_admin'), config('settings.roles.agent_employee')]);
            })->pluck('name', 'id');
    }

    /**
     * Users belong to Customer
     * @return mixed
     */
    public static function getUsersByOnlyCustomer()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', config('settings.roles.customer'));
        })->pluck('name', 'id');
    }

    /**
     * Users belong to my branch
     * @return mixed
     */
    public static function getUsersByOnlyBranch()
    {
        return User::where('branch_id', '=', \Auth::user()->branch_id)
            ->pluck('name', 'id');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
