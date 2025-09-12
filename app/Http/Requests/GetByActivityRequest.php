<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetByActivityRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        $apiKey = $this->header('X-API-KEY') ?? $this->query('api_key');
        return $apiKey === config('app.api_key');
    }

    public function rules(): array
    {
        return [
            'activityId' => 'required|integer|min:1',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'activityId' => $this->route('activityId'),
        ]);
    }

    public function messages(): array
    {
        return [
            'activityId.required' => 'ID вида деятельности обязателен',
            'activityId.integer' => 'ID вида деятельности должен быть числом',
            'activityId.min' => 'ID вида деятельности должен быть >= 1',
        ];
    }

}
