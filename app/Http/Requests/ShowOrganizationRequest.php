<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class ShowOrganizationRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1|exists:organizations,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    public function messages(): array
    {
        return [
            'id.required' => 'ID организации обязателен',
            'id.integer' => 'ID организации должен быть числом',
            'id.min' => 'ID организации должен быть больше 0',
            'id.exists' => 'Организация с таким ID не найдена',
        ];
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new ValidationException($validator, response()->json([
            'error' => $errors[0] ?? 'Ошибка валидации',
        ], 422));
    }

}
