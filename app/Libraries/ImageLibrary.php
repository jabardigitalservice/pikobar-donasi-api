<?php

namespace App\Libraries;

use App\Models\Image as ImageModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as ImgMap;
use Webpatser\Uuid\Uuid;

/**
 * Class ImageLibrary.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Libraries
 */
class ImageLibrary
{
    private $rootDir = 'public/';

    public function saveUserImg($image, $dir, $name)
    {
        $id = Uuid::generate(4)->string;
        $resize = $this->resize($image, 128, 128);
        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . $dir . '/' . $id . '.' . $ext;
        Storage::disk()->put($full, $resize->encode());
        $modelImage = new ImageModel();
        $modelImage->id = $id;
        $nameSlug = Str::slug($name);
        $modelImage->name = Str::limit($nameSlug, 191, '');
        $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        $modelImage->path = $this->rootDir . $dir;
        $modelImage->image_url = $full;
        $modelImage->data_type = 'original'; //original/inherit
        $modelImage->save();
        $imageId = $modelImage->id;
        return $imageId;
    }

    public function saveTransferSlip($image, $dir, $name)
    {
        $nameSlug = Str::slug($name);
        $nameLimit =  Str::limit($nameSlug, 191, '');
        $id = Uuid::generate(4)->string;
        $resize = ImgMap::make($image);
        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . $dir . '/' . $nameLimit . '.' . $ext;
        Storage::disk()->put($full, $resize->encode());
        $modelImage = new ImageModel();
        $modelImage->id = $id;
        $modelImage->name = $nameLimit;
        $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        $modelImage->path = $this->rootDir . $dir;
        $modelImage->image_url = $full;
        $modelImage->data_type = 'original'; //original/inherit
        $modelImage->save();
        $imageId = $modelImage->id;
        return $imageId;
    }

    public function delete(ImageModel $model)
    {
        Storage::disk()->delete($model->path);
    }

    public function resize($raw, $standardWidth = 750, $standardHeight = 410)
    {
        $image = ImgMap::make($raw);
        $image = $image->resize($standardWidth, $standardHeight);
        return $image;
    }
}
