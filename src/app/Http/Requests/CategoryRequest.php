<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'banner' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please enter a title.',
            'title.max' => 'Title must be less than 255 characters.',
            'banner.required' => 'Please upload a banner.',
            'banner.image' => 'Banner must be an image.',
            'banner.mimes' => 'Banner must be a file of type: jpeg, png, jpg.',
            'banner.max' => 'Banner must be less than 2MB.',
        ];
    }
}
