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
            'activity_id' => 'sometimes',
            'activity_id.*' => 'integer|exists:activities,id',
            'include_descendants' => 'sometimes|boolean',

            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'radius' => 'sometimes|numeric|min:0.1|max:6371',

            'min_lat' => 'sometimes|numeric|between:-90,90',
            'max_lat' => 'sometimes|numeric|between:-90,90',
            'min_lng' => 'sometimes|numeric|between:-180,180',
            'max_lng' => 'sometimes|numeric|between:-180,180',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            $radiusFields = ['latitude', 'longitude', 'radius'];
            $hasSomeRadiusFields = count(array_intersect(array_keys($data), $radiusFields)) > 0;
            $hasAllRadiusFields = count(array_intersect(array_keys($data), $radiusFields)) === 3;

            if ($hasSomeRadiusFields && !$hasAllRadiusFields) {
                $validator->errors()->add('latitude', 'Для фильтра по радиусу необходимо указать latitude, longitude и radius');
            }

            $bboxFields = ['min_lat', 'max_lat', 'min_lng', 'max_lng'];
            $hasSomeBboxFields = count(array_intersect(array_keys($data), $bboxFields)) > 0;
            $hasAllBboxFields = count(array_intersect(array_keys($data), $bboxFields)) === 4;

            if ($hasSomeBboxFields && !$hasAllBboxFields) {
                $validator->errors()->add('min_lat', 'Для фильтра по bounding box необходимо указать min_lat, max_lat, min_lng и max_lng');
            }
        });
    }
    protected function prepareForValidation()
    {
        if ($this->has('activity_id') && is_string($this->activity_id)) {
            $this->merge([
                'activity_id' => explode(',', $this->activity_id),
            ]);
        }

        if ($this->has('include_descendants')) {
            $this->merge([
                'include_descendants' => filter_var(
                        $this->include_descendants,
                        FILTER_VALIDATE_BOOLEAN,
                        FILTER_NULL_ON_FAILURE
                    ) ?? false,
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'latitude.numeric' => 'Широта должна быть числом',
            'longitude.numeric' => 'Долгота должна быть числом',
            'radius.numeric' => 'Радиус должен быть числом',

            'min_lat.numeric' => 'Минимальная широта должна быть числом',
            'max_lat.numeric' => 'Максимальная широта должна быть числом',
            'min_lng.numeric' => 'Минимальная долгота должна быть числом',
            'max_lng.numeric' => 'Максимальная долгота должна быть числом',

            'latitude.between' => 'Широта должна быть между -90 и 90 градусами',
            'longitude.between' => 'Долгота должна быть между -180 и 180 градусами',
            'min_lat.between' => 'Минимальная широта должна быть между -90 и 90 градусами',
            'max_lat.between' => 'Максимальная широта должна быть между -90 и 90 градусами',
            'min_lng.between' => 'Минимальная долгота должна быть между -180 и 180 градусами',
            'max_lng.between' => 'Максимальная долгота должна быть между -180 и 180 градусами',

            'radius.min' => 'Радиус должен быть не менее 0.1 км',
            'radius.max' => 'Радиус должен быть не более 6371 км (радиус Земли)',

            'name.string' => 'Название должно быть строкой',
            'name.min' => 'Название должно содержать хотя бы 1 символ',
            'building_id.integer' => 'ID здания должен быть целым числом',
            'building_id.exists' => 'Указанное здание не существует',
            'activity_id.*.integer' => 'ID активности должен быть целым числом',
            'activity_id.*.exists' => 'Указанная активность не существует',
            'include_descendants.boolean' => 'Параметр include_descendants должен быть true или false',
        ];
    }

}

