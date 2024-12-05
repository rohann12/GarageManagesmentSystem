<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('Customers.index', compact('customers'));
    }

    public function create()
    {
        return view('Customers.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        $customer = Customer::create($validatedData);

        return redirect()->route('Customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('Customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('Customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,'.$customer->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        $customer->update($validatedData);

        return redirect()->route('Customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('Customers.index')->with('success', 'Customer deleted successfully.');
    }
}
