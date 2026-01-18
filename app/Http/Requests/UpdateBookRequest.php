<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookId = $this->route('book')->id;
        
        return [
            'ISBN' => "required|string|max:20|unique:books,ISBN,$bookId",
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'mortgage' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
        ];
    }
}