<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::all();
            return response()->json([
                'success' => true,
                'data' => $customers,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['messages' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'address' => 'required',
                'contact' => 'required',
            ]);

            $customer = Customer::Create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'address' => $request->address,
                'contact' => $request->contact,
            ]);

            return response()->json(
                [
                    'success' => true,
                    'data' => $customer,
                ],
                201,
            );
        } catch (ValidationException $e) {
            return response()->json(['messages' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $customer,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customer not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }
}
