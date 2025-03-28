<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'transaction_date' => 'required|date',
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $product = Product::find($this->input('product_id'));
                    if ($product && $product->stock < $value) {
                        $fail('Jumlah barang melebihi stok yang tersedia.');
                    }
                }
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk yang dipilih tidak valid.',

            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date' => 'Tanggal transaksi harus dalam format yang benar.',

            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.integer' => 'Jumlah barang harus berupa angka.',
            'quantity.min' => 'Jumlah barang minimal adalah 1.',
        ];
    }
}
