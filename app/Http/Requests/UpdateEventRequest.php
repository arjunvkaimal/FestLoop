<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'in:cultural,technical,sports,workshop,seminar,other'],
            'venue' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date', 'after_or_equal:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'registration_deadline' => ['required', 'date', 'before:start_time'],
            'capacity_limit' => ['required', 'integer', 'min:1', 'max:10000'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['sometimes', 'string', 'in:draft,published'],
        ];
    }
}
