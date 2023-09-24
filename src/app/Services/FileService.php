<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function s3Upload(UploadedFile $file, string $path): string
    {
        $fileName = $this->fileName($file);
        $file->storeAs($path, $fileName, 's3');

        return $fileName;
    }

    public function s3Delete(string $fileName, string $path): bool
    {
        return Storage::disk('s3')->delete($path . '/' . $fileName);
    }

    public function s3DeleteMultiple(array $fileNames, string $path): bool
    {
        $mergedUrls = $this->prepareAwsUrls($fileNames, $path);

        return Storage::disk('s3')->delete($mergedUrls);
    }

    private function fileName(UploadedFile $file): string
    {
        return time() . '_' . $file->getClientOriginalName();
    }

    private function prepareAwsUrls(array $fileNames, string $path): array
    {
        $mergedUrls = [];

        foreach ($fileNames as $fileName) {
            $mergedUrls[] = $path . '/' . $fileName;
        }

        return $mergedUrls;
    }
}
