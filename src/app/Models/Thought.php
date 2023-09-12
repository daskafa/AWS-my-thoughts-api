<?php

namespace App\Models;

use App\Constants\CommonConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Thought extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
    ];

    protected $appends = ['photo_s3_full_url', 'type_name'];

    public function getPhotoS3FullUrlAttribute()
    {
        return Storage::disk('s3')->url(CommonConstants::THOUGHT_PHOTO_S3_BASE_PATH . '/' . $this->photo);
    }

    public function getTypeNameAttribute()
    {
        return CommonConstants::THOUGHT_TYPES[$this->type];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
