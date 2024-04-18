<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequirementStoreRequest extends FormRequest
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
            'client_id' => 'required',
            'location' => 'required',
            'position' => 'required',
            'total_position' => 'required|integer',
            'min_years' => 'required|integer',
            'max_years' => 'required|integer',
            'salary_min' => 'required|integer',
            'salary_max' => 'required|integer',
            'requirement_status' => 'required',
            'skills' => 'required|string',
            'jd' => 'required|string'
        ];
    }
}
