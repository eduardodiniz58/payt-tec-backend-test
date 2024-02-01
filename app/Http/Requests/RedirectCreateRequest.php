<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UrlNotSelfValidationRule;


class RedirectCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'url' => [
                'required',
                'url',
                'active_url',
                new UrlNotSelfValidationRule,
                'starts_with:https', // Garante que a URL seja HTTPS
            ],
        ];
    }
}
