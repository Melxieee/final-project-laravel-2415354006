<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        return view('services.index', compact('status'));
    }

    public function store(Request $request)
    {
        if ($request->has('price')) {
            $request->merge(['price' => (int) $request->input('price')]);
        }

        $apiController = app(\App\Http\Controllers\Api\ServiceController::class);
        $response = $apiController->store($request);

        if ($response->getStatusCode() === 201) {
            return redirect()->route('web.services.index')->with('success', 'Service created successfully.');
        }

        $data = $response->getData(true);
        if ($response->getStatusCode() === 422) {
            return back()->withErrors($data['errors'] ?? [])->withInput();
        }
        return back()->with('error', $data['message'] ?? 'Failed to create service.')->withInput();
    }

    public function update(Request $request, $id)
    {
        if ($request->has('price')) {
            $request->merge(['price' => (int) $request->input('price')]);
        }

        $apiController = app(\App\Http\Controllers\Api\ServiceController::class);
        $response = $apiController->update($request, (int)$id);

        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.services.index')->with('success', 'Service updated successfully.');
        }

        $data = $response->getData(true);
        if ($response->getStatusCode() === 422) {
            return back()->withErrors($data['errors'] ?? [])->withInput();
        }
        return back()->with('error', $data['message'] ?? 'Failed to update service.')->withInput();
    }

    public function destroy($id)
    {
        $apiController = app(\App\Http\Controllers\Api\ServiceController::class);
        $response = $apiController->destroy((int)$id);

        $data = $response->getData(true);
        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.services.index')->with('success', 'Service deleted successfully.');
        }

        return back()->with('error', $data['message'] ?? 'Failed to delete service.');
    }

    public function activate($id)
    {
        $apiController = app(\App\Http\Controllers\Api\ServiceController::class);
        $response = $apiController->activate((int)$id);

        $data = $response->getData(true);
        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.services.index')->with('success', 'Service activated successfully.');
        }

        return back()->with('error', $data['message'] ?? 'Failed to activate service.');
    }

    public function deactivate($id)
    {
        $apiController = app(\App\Http\Controllers\Api\ServiceController::class);
        $response = $apiController->deactivate((int)$id);

        $data = $response->getData(true);
        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.services.index')->with('success', 'Service deactivated successfully.');
        }

        return back()->with('error', $data['message'] ?? 'Failed to deactivate service.');
    }
}
