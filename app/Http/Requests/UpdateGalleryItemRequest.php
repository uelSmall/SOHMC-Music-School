<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('edit_backend');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'status' => 'required|integer|in:0,1,2',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a title for this gallery image.',
            'image.image' => 'The selected file must be an image.',
            'image.max' => 'The image may not be greater than 10MB.',
        ];
    }
}
