<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('subscriptions.index');
    }

    public function store(Request $request)
    {
        $apiController = app(\App\Http\Controllers\Api\SubscriptionController::class);
        $response = $apiController->store($request);

        if ($response->getStatusCode() === 201) {
            return redirect()->route('web.subscriptions.index')->with('success', 'Subscription created successfully.');
        }

        $data = $response->getData(true);
        if ($response->getStatusCode() === 422) {
            return back()->withErrors($data['errors'] ?? [])->withInput();
        }
        return back()->with('error', $data['message'] ?? 'Failed to create subscription.')->withInput();
    }

    public function update(Request $request, $id)
    {
        $apiController = app(\App\Http\Controllers\Api\SubscriptionController::class);
        $response = $apiController->update($request, (int)$id);

        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.subscriptions.index')->with('success', 'Subscription updated successfully.');
        }

        $data = $response->getData(true);
        if ($response->getStatusCode() === 422) {
            return back()->withErrors($data['errors'] ?? [])->withInput();
        }
        return back()->with('error', $data['message'] ?? 'Failed to update subscription.')->withInput();
    }

    public function destroy($id)
    {
        $apiController = app(\App\Http\Controllers\Api\SubscriptionController::class);
        $response = $apiController->destroy((int)$id);

        $data = $response->getData(true);
        if ($response->getStatusCode() === 200) {
            return redirect()->route('web.subscriptions.index')->with('success', 'Subscription deleted successfully.');
        }

        return back()->with('error', $data['message'] ?? 'Failed to delete subscription.');
    }
}
