<?php

namespace App\Constants;

class CommonConstants
{
    const THOUGHT_TYPES = [
        1 => 'Good Thought',
        2 => 'Bad Thought',
    ];

    const GENERAL_EXCEPTION_ERROR_MESSAGE = 'An unexpected error occurred. Please try again.';

    const NOT_AUTHORIZED_ERROR_MESSAGE = 'You are not authorized to access this resource.';

    const DEFAULT_ADMIN_ROLE = 'admin';

    const DEFAULT_USER_ROLE = 'user';

    const CATEGORY_BANNER_S3_BASE_PATH = 'categories/banners';

    const THOUGHT_PHOTO_S3_BASE_PATH = 'thoughts/photos';
}
