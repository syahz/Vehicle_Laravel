<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $customers = Vehicle::all();
            return response()->json([
                'success' => true,
                'data' => $customers,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['messages' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vehicle_type' => 'required|in:mobil,motor',
                'vehicle_name' => 'required|max:20',
                'vehicle_price' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            $vehicle = Vehicle::Create([
                'id' => Str::uuid(),
                'vehicle_type' => $request->vehicle_type,
                'vehicle_name' => $request->vehicle_name,
                'vehicle_price' => $request->vehicle_price,
            ]);

            return response()->json(
                [
                    'success' => true,
                    'data' => $vehicle,
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $customer = Vehicle::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $customer,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Vehicle not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
