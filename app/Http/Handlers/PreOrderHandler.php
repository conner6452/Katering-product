<?php

namespace App\Http\Handlers;

use App\Contracts\Interface\PreOrderInterface;
use App\Models\Ingredient;
use App\Models\PreOrder;

class PreOrderHandler
{
    protected $preOrderInterface;
    public function __construct(PreOrderInterface $preOrderInterface)
    {
        $this->preOrderInterface = $preOrderInterface;
    }

    public function store(array $data)
    {
        $ingredient = Ingredient::findOrFail($data['ingredient_id']);
        $quantity = $data['quantity'];
        $totalPrice = $ingredient->price * $quantity;

        // Cari pre order process untuk supplier ini
        $preOrder = PreOrder::where('supplier_id', $data['supplier_id'])
            ->where('status', 'process')
            ->first();

        if ($preOrder) {
            // Tambah item ke pre order yang sudah ada
            $preOrder->preOrderLists()->create([
                'ingredient_id' => $ingredient->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
            ]);
            // Update total_price
            $sum = $preOrder->preOrderLists()->sum('total_price');
            $preOrder->update(['total_price' => $sum]);
            return $preOrder->fresh(['supplier', 'preOrderLists.ingredient']);
        } else {
            // Buat pre order baru lewat repository
            $payload = [
                'supplier_id' => $data['supplier_id'],
                'items' => [[
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                ]],
            ];
            return $this->preOrderInterface->store($payload);
        }
    }
}
