<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NearOrganizationsRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        $apiKey = $this->header('X-API-KEY') ?? $this->query('api_key');
        return $apiKey === config('app.api_key');
    }

    public function rules(): array
    {
        return [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:0.1|max:6371',
        ];
    }

    public function messages(): array
    {
        return [
            'lat.required' => 'Широта (lat) является обязательным параметром',
            'lat.numeric' => 'Широта должна быть числом',
            'lat.between' => 'Широта должна быть в диапазоне от -90 до 90 градусов',

            'lng.required' => 'Долгота (lng) является обязательным параметром',
            'lng.numeric' => 'Долгота должна быть числом',
            'lng.between' => 'Долгота должна быть в диапазоне от -180 до 180 градусов',

            'radius.required' => 'Радиус поиска (radius) является обязательным параметром',
            'radius.numeric' => 'Радиус поиска должен быть числом',
            'radius.min' => 'Радиус поиска должен быть не менее 0.1 км',
            'radius.max' => 'Радиус поиска не может превышать радиус Земли (6371 км)',
        ];
    }
}
