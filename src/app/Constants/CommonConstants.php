<?php

namespace App\Constants;

class CommonConstants
{
    public const BASE_MODEL_NAMESPACE = 'App\Models\\';

    public const THOUGHT_TYPES = [
        1 => 'Good Thought',
        2 => 'Bad Thought',
    ];

    public const LIKABLE_MODEL_NAMES = [
        'comment' => self::BASE_MODEL_NAMESPACE . 'Comment',
        'thought' => self::BASE_MODEL_NAMESPACE . 'Thought',
    ];

    public const GENERAL_EXCEPTION_ERROR_MESSAGE = 'An unexpected error occurred. Please try again.';

    public const NOT_AUTHORIZED_ERROR_MESSAGE = 'You are not authorized to access this resource.';

    public const DEFAULT_ADMIN_ROLE = 'admin';

    public const DEFAULT_USER_ROLE = 'user';

    public const CATEGORY_BANNER_S3_BASE_PATH = 'categories/banners';

    public const THOUGHT_PHOTO_S3_BASE_PATH = 'thoughts/photos';
}
