<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $transactions = Transaction::all();
            return response()->json([
                'success' => true,
                'data' => $transactions,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required',
                'vehicle_id' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }
            // Convert Date
            $start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
            // Kalkulasi hari dari start_date to end_date
            $totalDays = $this->calculateDays($request->start_date, $request->end_date);
            // select price from vehicle then vehicle price times to total days 
            $vehicle = Vehicle::find($request->vehicle_id);
            $vehicle_price = $vehicle->vehicle_price;
            $total_price = $vehicle_price * $totalDays;
            // get the discount
            $discounts = Discount::all();
            foreach ($discounts as $discount) {
                if ($totalDays >= $discount->duration) {
                    $total_price = $total_price - $total_price * ($discount->discount / 100);
                    break;
                }
            }
            // insert to table transaction
            $transaction = Transaction::Create([
                'id' => Str::uuid(),
                'customer_id' => $request->customer_id,
                'vehicle_id' => $request->vehicle_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'total_price' => $total_price,
            ]);
            return response()->json(
                [
                    'message' => 'Success',
                    'data' => $transaction,
                ],201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Calculate Days
    private function calculateDays($startRent, $endRent)
    {
        $startRentCarbon = Carbon::createFromFormat('d/m/Y', $startRent);
        $endRentCarbon = Carbon::createFromFormat('d/m/Y', $endRent);

        $totalDays = $endRentCarbon->diffInDays($startRentCarbon);
        return $totalDays;
    }
}
