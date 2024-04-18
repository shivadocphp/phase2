<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateStoreRequest extends FormRequest
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
            'candidate_resume' => 'required|mimes:pdf,doc,docx',
            'candidate_name' => 'required',
            'gender' => 'required',
            'contact_no' => 'required|unique:candidate_basic_details|max:10',
            'whatsapp_no' => 'required|unique:candidate_basic_details|max:10',
            'candidate_email' => 'required|unique:candidate_basic_details|max:255',
            // 'current_company' => 'required',
            'preferred_location' => 'required',
            'employement_mode' => 'required',
            'pf_status' => 'required',
            'passport' => 'required',
            'preferred_shift' => 'required',
            'quali_level_id' => 'required',
            'quali_id' => 'required',
            'communication' => 'required',
            'skills' => 'required',
            'status' => 'required',
            'profile_source' => 'required',
        ];
    }
}
