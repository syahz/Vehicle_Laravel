<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Discount;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        try {
            $discounts = Discount::all();
            return response()->json([
                'success' => true,
                'data' => $discounts,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['messages' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name_discount' => 'required',
                'duration' => 'required|numeric',
                'discount' => 'required|numeric',
            ]);

            $discount = Discount::Create([
                'id' => Str::uuid(),
                'name_discount' => $request->name_discount,
                'duration' => $request->duration,
                'discount' => $request->discount,
            ]);

            return response()->json(
                [
                    'success' => true,
                    'data' => $discount,
                ],
                201,
            );
        } catch (ValidationException $e) {
            return response()->json(['messages' => $e->getMessage()], 422);
        }
    }
}
