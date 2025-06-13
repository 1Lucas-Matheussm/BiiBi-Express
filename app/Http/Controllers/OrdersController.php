<?php

namespace App\Http\Controllers;

use App\Models\orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Orders::with(['user', 'deliveryPerson', 'company'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'delivery_person_id' => 'nullable|exists:users,id',
            'company_id' => 'nullable|exists:users,id',
            'status' => 'in:pendente,em_andamento,entregue,cancelado',
            'origin_address' => 'required|string',
            'destination_address' => 'required|string',
            'total_price' => 'required|numeric',
            'payment_method' => 'required|in:pix,cartao,dinheiro',
            'observations' => 'nullable|string',
            'altura' => 'nullable|numeric',
            'comprimento' => 'nullable|numeric',
        ]);

        $order = Orders::create($validated);

        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Orders $order)
    {
        return response()->json($order->load(['user', 'deliveryPerson', 'company']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orders $order)
    {
        $validated = $request->validate([
            'delivery_person_id' => 'nullable|exists:users,id',
            'company_id' => 'nullable|exists:users,id',
            'status' => 'in:pendente,em_andamento,entregue,cancelado',
            'origin_address' => 'string',
            'destination_address' => 'string',
            'total_price' => 'numeric',
            'payment_method' => 'in:pix,cartao,dinheiro',
            'observations' => 'nullable|string',
            'altura' => 'nullable|numeric',
            'comprimento' => 'nullable|numeric',
        ]);

        $order->update($validated);

        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orders $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}
