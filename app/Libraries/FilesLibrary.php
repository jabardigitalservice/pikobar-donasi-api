<?php

namespace App\Libraries;

use App\Models\Files;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;

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
}