<?php

namespace App\Validators;

class ItemValidator extends BaseValidator
{
    protected $request;

    public function rules(): array
    {
        switch ($this->request->method()) {
            case 'GET':
                break;

            case 'POST':
                return [
                    'name'       => ['required'],
                    'description' => ['required'],
                ];
            case 'PATCH':
                return [
                    'name'       => ['required'],
                    'description' => ['required'],
                ];
            case 'PUT':
                break;
            case 'DELETE':
                break;
            default:
                break;
        }
    }

    public function messages(): array
    {
        $messages = parent::messages();

        $includesMessages = [
            'name_en.required'   => 'Name (English) is required.',
            'name_bn.required'   => 'Name (Bengali) is required.',
        ];

        return array_merge($messages, $includesMessages);
    }

    public function attributes(): array
    {
        $attributes = parent::attributes();

        $includesAttributes = [
            'name_en' => 'Name (English)',
            'name_bn' => 'Name (Bengali)',
        ];

        return array_merge($attributes, $includesAttributes);
    }
}
