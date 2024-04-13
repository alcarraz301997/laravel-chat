<?php

namespace App\Http\Requests;

use App\Traits\HasResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ConversationsRequest extends FormRequest
{
    use HasResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['nullable','string', 'max:100']
        ];

        if ($this->isMethod('POST')) {
            $rules['type_id'] = ['required','integer'];
            $rules['user_id'] = $this->input('type_id') == 1 ? ['required', 'integer'] : [];
            $rules['users_id'] = $this->input('type_id') == 2 ? ['required', 'array'] : [];
        }

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Formato inválido.', 400, $validator->errors()));
    }
}
