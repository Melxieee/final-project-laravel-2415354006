<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        return view('customers.index', compact('status'));
    }

    public function store(Request $request)
    {
        $apiController = app(\App\Http\Controllers\Api\CustomerController::class);
        $response = $apiController->store($request);

        if ($response->getStatusCode() === 201) {
            return redirect()->route('web.customers.index')->with('success', 'Customer created successfully.');
        }

        $data = $response->getData(true);
        if ($response->getStatusCode() === 422) {
            return back()->withErrors($data['errors'] ?? [])->withInput();
        }
        return back()->with('error', $data['message'] ?? 'Failed to create customer.')->withInput();
    }

    public function update(Request $request, $id)
    {
        $apiController = app(\App\Http\Controllers\Api\CustomerController::class);
        $response = $apiController->update($request, (int)$id);

        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.customers.index')->with('success', 'Customer updated successfully.');
        }

        $data = $response->getData(true);
        if ($response->getStatusCode() === 422) {
            return back()->withErrors($data['errors'] ?? [])->withInput();
        }
        return back()->with('error', $data['message'] ?? 'Failed to update customer.')->withInput();
    }

    public function destroy($id)
    {
        $apiController = app(\App\Http\Controllers\Api\CustomerController::class);
        $response = $apiController->destroy((int)$id);

        $data = $response->getData(true);
        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.customers.index')->with('success', 'Customer deleted successfully.');
        }

        return back()->with('error', $data['message'] ?? 'Failed to delete customer.');
    }

    public function activate($id)
    {
        $apiController = app(\App\Http\Controllers\Api\CustomerController::class);
        $response = $apiController->activate((int)$id);

        $data = $response->getData(true);
        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.customers.index')->with('success', 'Customer activated successfully.');
        }

        return back()->with('error', $data['message'] ?? 'Failed to activate customer.');
    }

    public function deactivate($id)
    {
        $apiController = app(\App\Http\Controllers\Api\CustomerController::class);
        $response = $apiController->deactivate((int)$id);

        $data = $response->getData(true);
        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.customers.index')->with('success', 'Customer deactivated successfully.');
        }

        return back()->with('error', $data['message'] ?? 'Failed to deactivate customer.');
    }
}
