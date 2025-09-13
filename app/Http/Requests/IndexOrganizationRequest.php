<?php

namespace App\Http\Requests;
class IndexOrganizationRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|min:1',
            'building_id' => 'sometimes|integer|exists:buildings,id',
            'activity_id' => 'sometimes|integer|exists:activities,id',
            'include_descendants' => 'sometimes|boolean',

            'latitude' => 'sometimes|required_with:longitude,radius|numeric|between:-90,90',
            'longitude' => 'sometimes|required_with:latitude,radius|numeric|between:-180,180',
            'radius' => 'sometimes|required_with:latitude,longitude|numeric|min:0.1|max:6371',

            'min_lat' => 'sometimes|required_with:max_lat,min_lng,max_lng|numeric|between:-90,90',
            'max_lat' => 'sometimes|required_with:min_lat,min_lng,max_lng|numeric|between:-90,90',
            'min_lng' => 'sometimes|required_with:min_lat,max_lat,max_lng|numeric|between:-180,180',
            'max_lng' => 'sometimes|required_with:min_lat,max_lat,min_lng|numeric|between:-180,180',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Название должно быть строкой',
            'name.min' => 'Название должно содержать минимум 1 символ',
            'building_id.integer' => 'ID здания должно быть числом',
            'building_id.exists' => 'Здание с таким ID не найдено',
            'activity_id.integer' => 'ID деятельности должен быть числом',
            'activity_id.exists' => 'Деятельность с таким ID не найдено',
            'include_descendants.boolean' => 'include_descendants должен быть булевым значением',
            'latitude.required_with' => 'Широта обязательна при указании долготы и радиуса',
            'latitude.numeric' => 'Широта должна быть числом',
            'latitude.between' => 'Широта должна быть от -90 до 90',
            'longitude.required_with' => 'Долгота обязательна при указании широты и радиуса',
            'longitude.numeric' => 'Долгота должна быть числом',
            'longitude.between' => 'Долгота должна быть от -180 до 180',
            'radius.required_with' => 'Радиус обязателен при указании широты и долготы',
            'radius.numeric' => 'Радиус должен быть числом',
            'radius.min' => 'Радиус не может быть меньше 0.1 км',
            'radius.max' => 'Радиус не может превышать 6371 км',
            'min_lat.required_with' => 'min_lat обязателен при указании остальных координат прямоугольника',
            'min_lat.numeric' => 'min_lat должно быть числом',
            'min_lat.between' => 'min_lat должно быть от -90 до 90',
            'max_lat.required_with' => 'max_lat обязателен при указании остальных координат прямоугольника',
            'max_lat.numeric' => 'max_lat должно быть числом',
            'max_lat.between' => 'max_lat должно быть от -90 до 90',
            'min_lng.required_with' => 'min_lng обязателен при указании остальных координат прямоугольника',
            'min_lng.numeric' => 'min_lng должно быть числом',
            'min_lng.between' => 'min_lng должно быть от -180 до 180',
            'max_lng.required_with' => 'max_lng обязателен при указании остальных координат прямоугольника',
            'max_lng.numeric' => 'max_lng должно быть числом',
            'max_lng.between' => 'max_lng должно быть от -180 до 180',
        ];
    }

}

