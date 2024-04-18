<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdateRequest extends FormRequest
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
            'candidate_name'=>'required',
            'contact_no' => 'required|unique:candidate_basic_details|max:10',
            'whatsapp_no' => 'required|unique:candidate_basic_details|max:10',
            'candidate_email'=> 'required|unique:candidate_basic_details|max:255',
        ];
    }
}
