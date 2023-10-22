<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'thought_id' => 'required|exists:thoughts,id',
            'comment' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'thought_id.required' => 'Thought information could not be found.',
            'thought_id.exists' => 'Thought information could not be found.',
            'comment.required' => 'Please enter a comment.',
        ];
    }
}
