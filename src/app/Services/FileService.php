<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService
{
    public function s3Upload($file, $path)
    {
        $fileName = $this->fileName($file);
        $file->storeAs($path, $fileName, 's3');

        return $fileName;
    }

    public function s3Delete($fileName, $path)
    {
        return Storage::disk('s3')->delete($path . '/' . $fileName);
    }

    private function fileName($file)
    {
        return time() . '_' . $file->getClientOriginalName();
    }
}
