<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SembakoPackageItem extends Model
{
    use SoftDeletes;

    public $table = 'sembako_package_items';

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
        'item_name',
        'item_sku',
        'quantity',
        'uom',
        'uom_name',
        'package_description',
        'last_modified_by',
        'deleted_by',
        'status'
    ];

    public static function sql()
    {
        return self::select(
            'sembako_package_items.id',
            'sembako_package_items.item_name',
            'sembako_package_items.item_sku',
            'sembako_package_items.quantity',
            'sembako_package_items.package_description',
            'sembako_package_items.uom_name',
            'sembako_package_items.status',
            'sembako_package_items.created_at',
            'sembako_package_items.updated_at'
        );
    }

    public function packages()
    {
        return $this->belongsToMany(SembakoPackageItem::class, 'sembako_many', 'item_id', 'package_id');
    }
}