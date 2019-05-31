<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditRSFiveRequest extends FormRequest
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
             'total_lesson' =>'required'
           ];
     }

     public function messages()
     {
        return [
            'total_lesson.required'  => 'Необходимо указать количество лекций'
        ];
     }
}
