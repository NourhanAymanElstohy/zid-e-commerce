<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'price' => 'required|numeric',
            'shipping_cost' => 'nullable|numeric',
            'is_vat_included' => 'nullable|numeric',
            'vat_percentage' => 'nullable|numeric|required_if:is_vat_included,0',
            'store_id' => 'required|exists:stores,id',
            'quantity' => 'numeric|nullable',
        ];
    }
}
