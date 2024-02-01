<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UrlNotSelfValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Obtém a URL base da aplicação
        $baseUrl = config('app.url');

        // Verifica se a URL de destino não aponta para a própria aplicação
        return strpos($value, $baseUrl) === false;
    }

    public function message()
    {
        return 'A URL de destino não pode apontar para a própria aplicação.';
    }
}
