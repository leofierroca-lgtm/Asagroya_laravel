<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $query = Customer::query();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('tax_id', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name')->get();

        return view('customers.index', compact('customers', 'search'));
    }

    public function create()
    {
        return view('customers.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_id' => 'required|string|max:50|unique:customers,tax_id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer = new Customer;
        $customer->tax_id = $validated['document_id'];
        $customer->name = $validated['name'];
        $customer->email = $validated['email'] ?? null;
        $customer->phone = $validated['phone'] ?? null;
        $customer->address = $validated['address'] ?? null;
        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Cliente registrado exitosamente.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'document_id' => 'required|string|max:50|unique:customers,tax_id,'.$customer->id,
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer->tax_id = $validated['document_id'];
        $customer->name = $validated['name'];
        $customer->email = $validated['email'] ?? null;
        $customer->phone = $validated['phone'] ?? null;
        $customer->address = $validated['address'] ?? null;
        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Datos del cliente actualizados.');
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();

            return redirect()->route('customers.index')->with('success', 'Cliente eliminado.');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'No se puede eliminar el cliente porque tiene ventas asociadas.');
        }
    }
}
