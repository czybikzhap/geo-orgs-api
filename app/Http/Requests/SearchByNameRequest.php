<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchByNameRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        $apiKey = $this->header('X-API-KEY') ?? $this->query('api_key');
        return $apiKey === config('app.api_key');
    }

    public function rules(): array
    {
        return [
            'q' => 'required|string|min:1',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'q' => $this->query('q')
        ]);
    }

    public function messages(): array
    {
        return [
            'q.required' => 'Параметр поиска q обязателен',
            'q.string' => 'Параметр поиска должен быть строкой',
            'q.min' => 'Параметр поиска должен содержать минимум 2 символа',
        ];
    }

}
