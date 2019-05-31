<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRSFiveRequest extends FormRequest
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
             'name' => 'required',
             'id_teacher' => 'required',
             'id_institution' => 'required',
             'id_discipline' => 'required',
             'id_group' =>  'required',
             'total_lesson' =>'required',
           ];
     }

     public function messages()
     {
        return [
            'name.required' => 'Название БРС обязательно к заполнению',
            'total_lesson.required'  => 'Необходимо указать количество лекций'
        ];
     }
}
