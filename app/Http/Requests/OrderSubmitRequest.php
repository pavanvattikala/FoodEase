<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderSubmitRequest extends FormRequest
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
            //
            'source' => 'required',
            'tableId' => 'nullable',
            'specialInstructions' => 'nullable',
            'isPickUpOrder' => 'required',
            'paymentMethod' => 'required',
            'billTable' => 'required',
            'order' => 'required',
        ];
    }
}
