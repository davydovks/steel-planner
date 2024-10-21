<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertCustomerRequest;
use App\Models\Customer;
use App\Models\CustomerType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Inertia\Response;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customer::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('Customers/Index', [
            'filters' => Request::all(['search', 'trashed']),
            'customers' => Customer::orderBy('name')
                ->filter(Request::only(['search', 'trashed']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($customer) => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'deleted_at' => $customer->deleted_at,
                    'weight' => $customer->weight,
                    'completed_weight' => 0,
                    'percent' => 0,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Customers/Create', [
            'customer_types' => CustomerType::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertCustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());

        return to_route('customers.index')->with('success', 'Клиент добавлен.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return inertia('Customers/Show', [
            'customer' => [
                'id' => $customer->id,
                'customer_type_id' => $customer->customerType->id,
                'type' => $customer->customerType->name,
                'name' => $customer->name,
                'address' => $customer->address,
                'TIN' => $customer->TIN,
                'deleted_at' => $customer->deleted_at,
                'orders' => $customer->orders()
                    ->orderBy('id')
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
            ],
            'filters' => Request::all(['search', 'trashed']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return inertia('Customers/Edit', [
            'customer' => [
                'id' => $customer->id,
                'customer_type_id' => $customer->customerType->id,
                'name' => $customer->name,
                'address' => $customer->address,
                'TIN' => $customer->TIN,
                'deleted_at' => $customer->deleted_at,
            ],
            'customer_types' => CustomerType::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return to_route('customers.show', [$customer])->with('success', 'Данные обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->orders()->exists()) {
            return back()->with('error', 'Невозможно удалить клиента, у которого есть заказы.');
        }
        $customer->delete();

        return to_route('customers.index')->with('success', 'Клиент удален.');
    }

    public function restore(Customer $customer): RedirectResponse
    {
        $customer->restore();

        return to_route('customers.show', [$customer])->with('success', 'Клиент восстановлен.');
    }
}
