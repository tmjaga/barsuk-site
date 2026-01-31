<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled(['order_date', 'order_time'])) {
            $this->merge([
                'order_start' => Carbon::parse($this->order_date.' '.$this->order_time)->format('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'names' => 'required',
            'email' => 'required|email',
            'order_date' => 'required|date',
            'order_time' => 'required|date_format:H:i',
            'phone' => 'required|regex:/^\+?[0-9]+$/|min:10',
            'status' => ['required', new Enum(OrderStatus::class)],
            'services' => 'required|array|min:1',
            'order_start' => 'sometimes|date',
        ];
    }
}
