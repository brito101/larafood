<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => $this->price ? str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $this->price))) : 0,
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "required|min:3|max:255|unique:plans,name,{$this->id},id",
            'description' => 'nullable|min:3|max:255',
            'price' => "required|regex:/^\d+(\.\d{1,2})?$/",
        ];
    }
}
