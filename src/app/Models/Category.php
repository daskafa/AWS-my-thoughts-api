<?php

namespace App\Models;

use App\Constants\CommonConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['banner_s3_full_url'];

    public function getBannerS3FullUrlAttribute()
    {
        return Storage::disk('s3')->url(CommonConstants::CATEGORY_BANNER_S3_BASE_PATH . '/' . $this->banner);
    }
}
