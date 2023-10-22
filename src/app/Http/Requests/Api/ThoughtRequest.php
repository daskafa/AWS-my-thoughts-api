<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ThoughtRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        $ruleArray = [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|max:255',
            'content' => 'required',
            'type' => 'required|in:1,2',
        ];

        if ($this->method() !== 'PUT') {
            $ruleArray['photo'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $ruleArray;
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Please select a valid category.',
            'photo.required' => 'Please upload a photo.',
            'photo.image' => 'Photo must be an image.',
            'photo.mimes' => 'Photo must be a file of type: jpeg, png, jpg.',
            'photo.max' => 'Photo must be less than 2MB.',
            'title.required' => 'Please enter a title.',
            'title.max' => 'Title must be less than 255 characters.',
            'content.required' => 'Please enter a content.',
            'type.required' => 'Please select a type.',
            'type.in' => 'Please select a valid type.',
        ];
    }
}
