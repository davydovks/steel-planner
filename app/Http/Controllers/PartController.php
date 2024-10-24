<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertPartRequest;
use App\Models\Order;
use App\Models\Part;
use Illuminate\Support\Facades\Request;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Parts/Index', [
            'filters' => Request::all(['search', 'trashed']),
            'parts' => Part::orderBy('order_id')
                ->orderBy('position')
                ->filter(Request::only(['search', 'trashed']))
                ->with('order')
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($orderId = null)
    {
        return inertia('Parts/Create', [
            'orders' => Order::all(['id', 'name', 'customer_id']),
            'order' => Order::find($orderId, ['id']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertPartRequest $request)
    {
        Part::create($request->validated());

        return to_route('parts.index')->with('success', 'Деталь добавлена.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Part $part)
    {
        return inertia('Parts/Show', [
            'filters' => Request::all(['search', 'trashed']),
            'part' => [
                'id' => $part->id,
                'position' => $part->position,
                'quantity' => $part->quantity,
                'profile' => $part->profile,
                'deleted_at' => $part->deleted_at,
                'order' => $part->order->only(['id', 'name']),
                'structures' => $part
                    ->structures()
                    ->orderBy('id')
                    ->paginate(10),
                'weight' => $part->weight,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Part $part)
    {
        return inertia('Parts/Edit', [
            'part' => [
                'id' => $part->id,
                'position' => $part->position,
                'profile' => $part->profile,
                'weight' => $part->weight,
                'deleted_at' => $part->deleted_at,
                'order' => $part->order->only(['id', 'name']),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertPartRequest $request, Part $part)
    {
        $part->update($request->validated());

        return to_route('parts.show', [$part])->with('success', 'Данные обновлены.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part)
    {
        //
    }
}
