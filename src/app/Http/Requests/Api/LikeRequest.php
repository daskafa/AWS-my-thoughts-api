<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'id' => 'required',
            'user_id' => 'required|exists:users,id',
            'is_like' => 'required|in:0,1',
            'type' => 'required|in:comment,thought',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Unexpected error occurred. Please try again.',
            'user_id.required' => 'User information could not be found.',
            'user_id.exists' => 'User information could not be found.',
            'is_like.required' => 'Like information could not be found.',
            'is_like.in' => 'Like information could not be found.',
            'type.required' => 'Type information could not be found.',
            'type.in' => 'Type information could not be found.',
        ];
    }
}
