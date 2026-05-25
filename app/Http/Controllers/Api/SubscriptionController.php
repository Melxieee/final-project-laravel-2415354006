<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Subscription::with(['customer', 'service'])
            ->latest();

        if ($request->query('all') === '1' || $request->query('all') === 'true') {
            $subscriptions = $query->get();
        } else {
            $subscriptions = $query->paginate(10)->withQueryString();
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscriptions retrieved successfully',
            'data' => $subscriptions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'service_id' => ['required', 'exists:services,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(['active', 'inactive', 'trial', 'isolir', 'dismantle'])],
        ]);

        $subscription = Subscription::query()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Subscription created successfully',
            'data' => $subscription->load(['customer', 'service']),
        ], 201);
    }

    public function show(int $subscription): JsonResponse
    {
        $subscription = Subscription::with(['customer', 'service'])->find($subscription);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'errors' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription retrieved successfully',
            'data' => $subscription,
        ]);
    }

    public function update(Request $request, int $subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'errors' => [],
            ], 404);
        }

        $data = $request->validate([
            'customer_id' => ['sometimes', 'exists:customers,id'],
            'service_id' => ['sometimes', 'exists:services,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['sometimes', Rule::in(['active', 'inactive', 'trial', 'isolir', 'dismantle'])],
        ]);

        $subscription->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Subscription updated successfully',
            'data' => $subscription->load(['customer', 'service']),
        ]);
    }

    public function destroy(int $subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'errors' => [],
            ], 404);
        }

        $subscription->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscription deleted successfully',
            'data' => null,
        ]);
    }
}
