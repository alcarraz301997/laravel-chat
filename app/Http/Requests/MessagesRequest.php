<?php

namespace App\Http\Requests;

use App\Traits\HasResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MessagesRequest extends FormRequest
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
        return [
            'conversation_id' => ['required', 'integer', 'validate_ids_exist:Conversations'],
            'type_content_id' => ['required', 'integer', 'validate_ids_exist:ContentTypes'],
            'content' => $this->input('type_content_id') == 1 ? ['required', 'string'] : ['required', 'file', 'mimes:jpeg,png,mp4,avi,mov,wmv,pdf,docx,txt,doc,xls,xlsx,ppt,pptx']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Formato inválido.', 400, $validator->errors()));
    }
}
