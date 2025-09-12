<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetByBuildingRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        $apiKey = $this->header('X-API-KEY') ?? $this->query('api_key');
        return $apiKey === config('app.api_key');
    }

    public function rules(): array
    {
        return [
            'buildingId' => 'required|integer|min:1',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'buildingId' => $this->route('buildingId'),
        ]);
    }

    public function messages(): array
    {
        return [
            'buildingId.required' => 'ID здания обязателен',
            'buildingId.integer' => 'ID здания должен быть числом',
            'buildingId.min' => 'ID здания должен быть >= 1',
        ];
    }

}
