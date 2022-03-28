<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'currentPage' => 'numeric',
            'perPage' => 'numeric',
            'orderBy' => 'string',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'currentPage.numeric' => trans('api.id.required'),
        ];
    }
}
