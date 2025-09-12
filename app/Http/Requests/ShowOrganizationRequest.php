<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowOrganizationRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        $apiKey = $this->header('X-API-KEY') ?? $this->query('api_key');
        return $apiKey === config('app.api_key');
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id')
        ]);
    }

    public function messages(): array
    {
        return [
            'id.required' => 'ID организации обязателен',
            'id.integer' => 'ID организации должен быть числом',
            'id.min' => 'ID организации должен быть >= 1',
        ];
    }

}
