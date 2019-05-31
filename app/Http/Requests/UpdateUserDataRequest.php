<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDataRequest extends FormRequest
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
             'ava' => 'max:1536',
             'login' => 'sometimes',
             'email'  => 'sometimes',
           ];
     }

     public function messages()
     {
        return [
            'ava' => 'Лимит размера файла превышен (1.5 Mb)',
        ];
     }
}
