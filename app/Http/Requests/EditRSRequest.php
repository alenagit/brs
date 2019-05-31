<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditRSRequest extends FormRequest
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
             'total_score' =>'required',
             'total_lesson' =>'required',
             'total_lesson_score' => 'required',
           ];
     }

     public function messages()
     {
        return [
            'total_score.required'  => 'Необходимо указать количество баллов',
            'total_lesson.required'  => 'Необходимо указать количество лекций',
            'total_lesson_score.required'  => 'Необходимо указать количсество баллов лекций',
        ];
     }
}
