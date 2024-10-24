<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStructureRequest;
use App\Http\Requests\UpdateStructureRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Structure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Structures/Index', [
            'filters' => Request::all(['search', 'trashed']),
            'structures' => Structure::orderBy('order_id')
                ->orderBy('id')
                ->filter(Request::only(['search', 'trashed']))
                ->with('order:id,name')
                ->paginate(10, [
                    'structures.id',
                    'structures.name',
                    'structures.quantity',
                    'structures.order_id',
                    'structures.deleted_at',
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($orderId = null)
    {
        return inertia('Structures/Create', [
            'orders' => Order::all(['id', 'name', 'customer_id']),
            'customers' => Customer::all(['id', 'name']),
            'order' => Order::find($orderId, ['id']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStructureRequest $request): RedirectResponse
    {
        Structure::create($request->validated());

        return to_route('structures.index')->with('success', 'Отправочная марка добавлена.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Structure $structure)
    {
        return inertia('Structures/Show', [
            'filters' => Request::all(['search', 'trashed']),
            'structure' => [
                'id' => $structure->id,
                'name' => $structure->name,
                'quantity' => $structure->quantity,
                'deleted_at' => $structure->deleted_at,
                'order' => $structure->order->only(['id', 'name']),
                'parts' => $structure
                    ->parts()
                    ->orderBy('position')
                    ->paginate(10),
                'weight' => $structure->weight,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Structure $structure)
    {
        return inertia('Structures/Edit', [
            'structure' => [
                'id' => $structure->id,
                'name' => $structure->name,
                'quantity' => $structure->quantity,
                'deleted_at' => $structure->deleted_at,
                'order' => $structure->order->only(['id', 'name']),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStructureRequest $request, Structure $structure): RedirectResponse
    {
        $structure->update($request->validated());

        return to_route('structures.show', [$structure])->with('success', 'Данные обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure): RedirectResponse
    {
        $structure->delete();

        return to_route('structures.index')->with('success', 'Отправочная марка удалена.');
    }

    public function restore(Structure $structure): RedirectResponse
    {
        $structure->restore();

        return to_route('structures.show', [$structure])->with('success', 'Отправочная марка восстановлена.');
    }
}
