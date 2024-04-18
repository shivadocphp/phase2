<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientBasicDetailStoreRequest extends FormRequest
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
            'company_name' => 'required|max:255',
            'company_emailID' => 'required',
            'company_contact_no' => 'required',
            'industry_type_id' => 'required',
            'ceo' => 'required',
            'ceo_contact' => 'required',
            'ceo_emailID' => 'required',
            'hr_spoc' => 'required',
            'hr_desig' => 'required',
            'fspoc' => 'required',
            'fspoc_designation' => 'required',
            'fspoc_contact' => 'required',
            'fspoc_email' => 'required',
            'client_status' => 'required',
            'website' => 'required',
        ];
    }
}
