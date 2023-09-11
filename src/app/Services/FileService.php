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

    public function s3DeleteMultiple(array $fileNames, $path)
    {
        $mergedUrls = $this->prepareAwsUrls($fileNames, $path);

        return Storage::disk('s3')->delete($mergedUrls);
    }

    private function fileName($file)
    {
        return time() . '_' . $file->getClientOriginalName();
    }

    private function prepareAwsUrls(array $fileNames, $path)
    {
        $mergedUrls = [];

        foreach ($fileNames as $fileName) {
            $mergedUrls[] = $path . '/' . $fileName;
        }

        return $mergedUrls;
    }
}
