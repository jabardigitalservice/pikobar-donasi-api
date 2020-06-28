<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'statistics';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'personal_investor',
        'company_investor',
        'total_goods',
        'total_cash',
        'last_key',
        'date_input',
        'is_last',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'date_input' => 'datetime:Y-m-d'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return mixed
     */
    public static function sql()
    {
        return self::select(
            'statistics.id',
            'statistics.personal_investor',
            'statistics.company_investor',
            'statistics.total_goods',
            'statistics.total_cash',
            'statistics.last_key',
            'statistics.date_input',
            'statistics.is_last',
            'statistics.created_at',
            'statistics.updated_at'
        );
    }
}