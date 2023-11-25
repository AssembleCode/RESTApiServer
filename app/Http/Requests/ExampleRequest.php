<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExampleRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        switch ($this->getMethod()) {
            case 'GET':
                # code...
                break;
            case 'POST':
                return [
                    'title' => 'required'
                ];
                break;
            case 'PATCH':
                return [
                    'title' => 'required'
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required'
                ];
                break;
            case 'DELETE':
                # code...
                break;

            default:
                # code...
                break;
        }
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The Title field is required',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Title'
        ];
    }
}
