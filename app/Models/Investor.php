<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investor extends Model
{
    use SoftDeletes;

    public $table = 'investors';

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
        'investor_name',
        'phone',
        'email',
        'address',
        'category_id',
        'category_slug',
        'category_name',
        'donate_id',
        'donate_category',
        'donate_category_name',
        'donate_status',
        'donate_status_name',
        'invoice_number',
        'attachment_id',
        'profile_picture',
        'show_name',
        'award_claim',
        'donate_date',
        'last_modified_by',
        'deleted_by',
    ];

    public function items()
    {
        return $this->hasMany('App\Models\InvestorItem', 'investor_id', 'id');
    }

    public function files()
    {
        return $this->belongsTo(Files::class, 'attachment_id', 'id');
    }
}