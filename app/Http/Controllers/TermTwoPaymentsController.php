<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\term_two_payments;
use App\Http\Requests\Storeterm_two_paymentsRequest;
use App\Http\Requests\Updateterm_two_paymentsRequest;

class TermTwoPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $term_two_payment = term_two_payments::where('user_id', $request->user_id)->first();
        if ($term_two_payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment already exists',
            ], 400);
        }
        $term_two_payment = new term_two_payments();
        $term_two_payment->user_id = $request->user_id;
        $term_two_payment->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Payment created successfully',
            'payment' => $term_two_payment,
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, term_two_payments $term_two_payments)
    {
     $request->validate([
        'user_id' => 'required|exists:users,id',
     ]);
        $term_two_payments->user_id = $request->user_id;
        $term_two_payments->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Payment updated successfully',
            'payment' => $term_two_payments,
        ], 200);
    }

 function destroy(term_two_payments $termOnePayment)
{
    $termOnePayment->delete();
    return response()->json([
        'status' => 'success',
        'message' => 'Payment deleted successfully',
    ], 200);
}

public function deleteAll()
{
    term_two_payments::query()->delete();
    return response()->json([
        'status' => 'success',
        'message' => 'All payments deleted successfully',
    ], 200);

}
}

