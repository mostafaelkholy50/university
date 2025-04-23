<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\term_one_payments;
use App\Http\Requests\Storeterm_one_paymentsRequest;
use App\Http\Requests\Updateterm_one_paymentsRequest;

class TermOnePaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
      $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $term_one_payment = term_one_payments::where('user_id', $request->user_id)->first();
        if ($term_one_payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment already exists',
            ], 400);
        }
        $term_one_payment = new term_one_payments();
        $term_one_payment->user_id = $request->user_id;
        $term_one_payment->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Payment created successfully',
            'payment' => $term_one_payment,
        ], 201);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(term_one_payments $term_one_payments)
    {
        $term_one_payments->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Payment deleted successfully',
        ], 200);
    }
    public function deleteAll()
    {
        term_one_payments::truncate();
        return response()->json([
            'status' => 'success',
            'message' => 'All payments deleted successfully',
        ], 200);
    }
}
