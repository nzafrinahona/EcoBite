<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'food_item_id' => ['required', 'exists:food_items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
