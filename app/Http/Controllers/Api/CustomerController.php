<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::withTrashed()
            ->typeCustomer()
            ->get();

        return response()->json($customers);
    }

    public function destroy(User $customer)
    {
        $customer->delete();

        return response()->json([], 204);
    }
}
