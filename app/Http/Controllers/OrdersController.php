<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public readonly Orders $Orders;

    public function __construct()
    {
        $this->Orders = new Orders();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = DB::table('orders')
            ->where('orders.status', 1)
            ->leftJoin('users as clientes', 'orders.user_id', '=', 'clientes.id')
            ->leftJoin('users as entregadores', 'orders.delivery_person_id', '=', 'entregadores.id')
            ->leftJoin('users as empresas', 'orders.company_id', '=', 'empresas.id')
            ->select(
                'orders.id',
                'orders.status',
                DB::raw("orders.status = 1 as isPending"),
                DB::raw("CASE
                        WHEN orders.status = 1 THEN 'pendente'
                        WHEN orders.status = 2 THEN 'em_andamento'
                     END as status_label"),
                'orders.origin_address',
                'orders.destination_address',
                'orders.total_price',
                'orders.payment_method',
                'orders.observations',

                'orders.package_size',
                DB::raw("CASE
                    WHEN orders.package_size = 1 THEN 'Pequeno'
                    WHEN orders.package_size = 2 THEN 'Médio'
                    WHEN orders.package_size = 3 THEN 'Grande'
                    ELSE 'Desconhecido'
                END as package_size_label"),

                'orders.fragile',
                DB::raw("CASE WHEN orders.fragile THEN 'Sim' ELSE 'Não' END as fragile_label"),


                // Cliente
                'clientes.name as userName',

                //entregador
                'entregadores.name as deliveryPersonName',

                // Empresa
                'empresas.name as enterpriseName',
                'empresas.company_name as enterpriseFantasyName',

                // Entregador (somente se o status não for pendente)
                DB::raw("CASE WHEN orders.status != 1 THEN entregadores.name ELSE NULL END as deliveryManName")
            )
            ->get();

        return response()->json([
            'data' => $orders
        ]);
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
            'status' => 'required|in:1,2',
            'origin_address' => 'required|string',
            'destination_address' => 'required|string',
            'total_price' => 'required|numeric',
            'payment_method' => 'required|in:pix,cartao,dinheiro',
            'package_size' => 'required|in:1,2,3',  // validação do package_size
            'observations' => 'required|string',
            'fragile' => 'required|boolean',
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
            'status' => 'in:1,2',
            'origin_address' => 'string',
            'destination_address' => 'string',
            'total_price' => 'numeric',
            'payment_method' => 'in:pix,cartao,dinheiro',
            'package_size' => 'in:1,2,3',  // validação do package_size
            'observations' => 'string',
            'fragile' => 'boolean'
        ]);

        $order->update($validated);

        return response()->json($order);
    }

    /**
     * Atualiza apenas o status do pedido.
     */
    public function updateStatus(Request $request, Orders $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:1,2',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Status atualizado com sucesso.',
            'order' => $order
        ]);
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
