<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SembakoPackage extends Model
{
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
    protected $dates = ['created_at', 'updated_at'];

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
        'status'
    ];

    public static function sql()
    {
        return self::select('*')->with('items');
    }

    /*public function items()
    {
        return $this->hasMany('App\Models\SembakoPackageItem', 'sembako_id', 'id');
    }*/

    public function items()
    {
        return $this->belongsToMany(SembakoPackageItem::class, 'sembako_many', 'package_id', 'item_id');
    }
}