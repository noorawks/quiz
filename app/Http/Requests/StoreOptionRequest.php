<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOptionRequest extends FormRequest
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
            'question_id' => [
                'required',
                'integer',
            ],
            'option_text' => [
                'required',
            ],
            'points'      => [
                'nullable',
                'integer',
                'min:0',
                'max:10',
            ],
        ];
    }
}
