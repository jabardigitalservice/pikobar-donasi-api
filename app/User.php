<?php

namespace App;

use App\Models\Image;
use App\Models\Role;
use App\Models\UserRole;
use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * App\User
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'gender',
        'email',
        'active',
        'avatar',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'users';

    /**
     * [OVERRIDE].
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public static function sql()
    {
        return self::
        select('users.*', 'users.id as id')
            ->with('roles');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'avatar', 'id');
    }

    public function findForPassport($identifier)
    {
        return $this->orWhere('email', $identifier)->orWhere('username', $identifier)->first();
    }

    public function roleone()
    {
        return $this->hasOne(UserRole::class);
    }

    public function user_role()
    {
        return $this->hasOne(UserRole::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, UserRole::class, 'user_id', 'role_id');
    }

    /**
     * Checking User has one or more role??
     *
     */
    public function hasAnyRole()
    {
        $data = $this->roles->first();
        if ($data === null) {
            return false;
        }
        return true;
    }

    /**
     * @return boolean
     */
    public function isActivated()
    {
        if ($this->active == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if user has role.
     *
     * <code>
     * $roles = auth()->user()->hasRole(['Owner', 'Administrator']);
     * dd($roles);
     * </code>
     *
     * @param $roles
     * @param string|null $slug
     * @return bool
     */
    public function hasRole($roles, string $slug = null)
    {
        if (is_string($roles) && $roles === '*') {
            return true;
        }

        if (is_string($roles) && false !== strpos($roles, '|')) {
            $roles = $this->convertPipeToArray($roles);
        }

        if (is_string($roles)) {
            return $this->roles->contains('slug', $roles);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role, $slug)) {
                    return true;
                }
            }
            return false;
        }

        return $roles->intersect($slug ? $this->roles->where('slug', $slug) : $this->roles)->isNotEmpty();
    }

    public function hasAnyRoles(...$roles)
    {
        return $this->hasRole($roles);
    }

    protected function convertPipeToArray(string $pipeString)
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return $pipeString;
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }

        if (!in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
    }
}
