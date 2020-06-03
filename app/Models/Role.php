<?php

namespace App\Models;

use App\Contracts\Model as ModelContracts;
use Illuminate\Database\Eloquent\Model;

/**
 * Role Model.
 *
 * @package App\Models
 */
class Role extends Model implements ModelContracts
{
    public $table = 'roles';

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
     * The "type" of the auto-incrementing ID.
     * @var string
     */
    public $keyType = 'string';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'slug',
        'role_name',
        'description',
        'is_active',
        'is_default'
    ];

    public function sql()
    {
        return $this
            ->select(
                $this->table . '.id',
                $this->table . '.slug',
                $this->table . '.role_name',
                $this->table . '.description',
                $this->table . '.is_active',
                $this->table . '.is_default'
            );
    }

    public function role_menu()
    {
        return $this->hasMany(MenuRole::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class,
            'menu_roles',
            'role_id',
            'menu_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,
            'permission_roles',
            'role_id',
            'permission_id');
    }

    public function user_role()
    {
        return $this->hasMany(UserRole::class);
    }

    public function findBySlug($slugName)
    {
        $role = static::where('slug', $slugName)->first();
        return $role;
    }
}
