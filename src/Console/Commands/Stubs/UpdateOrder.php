<?php

namespace App\Http\Requests\Admin\ProductSet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.product-set.edit');;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sortable_array' => ['required', 'array'],
            'sortable_array.*.id' => ['required', 'exists:product_sets'], 
        ];
    }
}