<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Orders/Index', [
            'filters' => Request::all(['search', 'trashed']),
            'orders' => Order::orderBy('name')
                ->filter(Request::only(['search', 'trashed']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($order) => [
                    'id' => $order->id,
                    'name' => $order->name,
                    'deleted_at' => $order->deleted_at,
                    'weight' => $order->weight,
                    'completed_weight' => 0,
                    'percent' => 0,
                ]),
            'customers' => Customer::withTrashed()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($customerId = null)
    {
        return inertia('Orders/Create', [
            'customers' => Customer::all(),
            'customer' => Customer::find($customerId, ['id']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertOrderRequest $request): RedirectResponse
    {
        Order::create($request->validated());

        return to_route('orders.index')->with('success', 'Заказ добавлен.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return inertia('Orders/Show', [
            'order' => [
                'id' => $order->id,
                'name' => $order->name,
                'due_date' => $order->due_date,
                'deleted_at' => $order->deleted_at,
                'weight' => $order->weight,
                'customer' => $order->customer()->withTrashed()->first(['id', 'name', 'deleted_at']),
                'structures' => $order->structures()
                    ->orderBy('id')
                    ->filter(Request::only(['search', 'trashed']))
                    ->paginate(10, [
                        'structures.id',
                        'structures.name',
                        'structures.quantity',
                        'structures.deleted_at',
                    ]),
                'parts' => $order->parts()
                    ->orderBy('position')
                    ->paginate(10),
            ],
            'filters' => Request::all(['search', 'trashed']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return inertia('Orders/Edit', [
            'order' => [
                'id' => $order->id,
                'name' => $order->name,
                'due_date' => $order->due_date,
                'customer_id' => $order->customer_id,
                'deleted_at' => $order->deleted_at,
            ],
            'customers' => Customer::all(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertOrderRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());

        return to_route('orders.show', [$order])->with('success', 'Данные обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        // TODO: Добавить защиту от удаления непустых заказов либо каскадировать удаление
        // if ($order->marks()->exists()) {
        //     return back()->with('error', 'Невозможно удалить заказ, у которого есть отправочные марки.');
        // }
        $order->delete();

        return to_route('orders.index')->with('success', 'Заказ удален.');
    }

    public function restore(Order $order): RedirectResponse
    {
        $order->restore();

        return to_route('orders.show', [$order])->with('success', 'Заказ восстановлен.');
    }
}
