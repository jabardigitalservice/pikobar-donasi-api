<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestorItem extends Model
{
    use SoftDeletes;

    public $table = 'investor_items';

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
        'investor_id',
        'investor_name',
        'investor_phone',
        'investor_email',
        'donate_category',
        'item_package_id',
        'item_package_sku',
        'item_package_name',
        'quantity',
        'bank_id',
        'bank_name',
        'bank_account',
        'bank_number',
        'amount',
        'last_modified_by',
        'deleted_by',
    ];
}