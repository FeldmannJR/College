<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpreadsheetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tabela' => 'required|file|mimeTypes:' .
                'application/vnd.ms-office,' .
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,' .
                'application/vnd.ms-excel,' .
                'application/vnd.oasis.opendocument.spreadsheet'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório!',
            'mime_types' => 'O tipo do arquivo :attribute não é compativel!'
        ];
    }
}
