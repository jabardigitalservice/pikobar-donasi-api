<?php

namespace App\Libraries;

use App\Models\Files;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;
use Intervention\Image\Facades\Image as ImgMap;

class FilesLibrary
{
    private $rootDir = 'public/investor/docs';

    public function saveDocument($file, $name)
    {
        $id = Uuid::generate(4)->string;
        $nameSlug = Str::slug($name);
        $name = Str::limit($nameSlug, 191, '');
        $ext = $file->getClientOriginalExtension();
        $full = $this->rootDir . '/' . $name . '.' . $ext;
        $save = $this->rootDir . '/';
        Storage::disk()->putFileAs($save, $file, $name . '.' . $ext);
        $model = new Files();
        $model->id = $id;
        $model->name = Str::limit($nameSlug, 191, '');
        $model->extension = Str::slug($file->getClientOriginalExtension());
        $model->path = $this->rootDir;
        $model->file_url = $full;
        $model->data_type = 'original';
        $model->save();
        $imageId = $model->id;
        return $imageId;
    }

    public function saveTransferSlip($image, $name)
    {
        $nameSlug = Str::slug($name);
        $nameLimit =  Str::limit($nameSlug, 191, '');
        $id = Uuid::generate(4)->string;
        $resize = ImgMap::make($image);
        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . '/transfer/' . $nameLimit . '.' . $ext;
        Storage::disk()->put($full, $resize->encode());
        $modelImage = new Files();
        $modelImage->id = $id;
        $modelImage->name = $nameLimit;
        $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        $modelImage->path = $this->rootDir;
        $modelImage->file_url = $full;
        $modelImage->data_type = 'original'; //original/inherit
        $modelImage->save();
        $imageId = $modelImage->id;
        return $imageId;
    }
}