<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerFeedback;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerFeedbackController extends Controller
{
    public function sendFeedback(Request $request)
    {
        $request->validate([
            'is_bug' => 'nullable|boolean',
            'reported_customer_id' => 'nullable',
            'feedback' => 'required|string'
        ]);

        if ($reportedCustId = $request->reported_customer_id) {
            if (! User::where('id', $reportedCustId)->exists()) {
                abort(404, 'Customer doesn\'t exists.');
            }
        }

        $newFeedback = CustomerFeedback::create([
            'reported_customer_id' => $reportedCustId ?? null,
            'feedback' => $request->feedback,
            'is_bug' => $request->boolean('is_bug'),
            'customer_id' => auth()->id()
        ]);

        return response()->json($newFeedback, 201);
    }
}
