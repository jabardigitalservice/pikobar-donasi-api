<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SembakoPackageItem extends Model
{
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
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sembako_id',
        'item_name',
        'item_sku',
        'quantity',
        'package_description',
        'last_modified_by',
        'status'
    ];
}