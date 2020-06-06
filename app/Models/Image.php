<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Image Model untuk store image.
 *
 * @package App\Models
 */
class Image extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'images';

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
        'name',
        'extension',
        'path',
        'file_type',
        'data_type',
        'image_url'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url'
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Get the image's full url.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return \Storage::url($this->attributes['image_url']);
    }
}
