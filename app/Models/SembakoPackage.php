<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SembakoPackage extends Model
{
    use SoftDeletes;

    public $table = 'sembako_packages';

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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['status' => 'boolean'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sku',
        'package_name',
        'package_description',
        'last_modified_by',
        'deleted_by',
        'status'
    ];

    public static function sql()
    {
        return self::select(
            'sembako_packages.id',
            'sembako_packages.sku',
            'sembako_packages.package_name',
            'sembako_packages.package_description',
            'sembako_packages.status',
            'sembako_packages.created_at',
            'sembako_packages.updated_at'
        )->with('items');
    }

    public function items()
    {
        return $this->belongsToMany(SembakoPackageItem::class, 'sembako_many', 'package_id', 'item_id');
    }
}