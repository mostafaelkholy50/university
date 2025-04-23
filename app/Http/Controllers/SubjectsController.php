<?php

namespace App\Http\Controllers;

use App\Models\subjects;
use Illuminate\Http\Request;
use App\Http\Requests\StoresubjectsRequest;
use App\Http\Requests\UpdatesubjectsRequest;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = subjects::all();
        return response()->json([
            'status' => 'success',
            'subjects' => $subjects,
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $request->validate([
        'name' => 'required',
        'specialty' => 'required',
        'term' => 'required',
        'years' => 'required',
      ]);
        $subject = new subjects();
        $subject->name = $request->name;
        $subject->specialty = $request->specialty;
        $subject->term = $request->term;
        $subject->years = $request->years;
        $subject->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Subject created successfully',
            'subject' => $subject,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(subjects $subjects)
    {
        return response()->json([
            'status' => 'success',
            'subject' => $subjects,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, subjects $subjects)
    {
       $request->validate([
        'name' => 'required',
        'specialty' => 'required',
        'term' => 'required',
        'years' => 'required',
       ]);
        $subjects->name = $request->name;
        $subjects->specialty = $request->specialty;
        $subjects->term = $request->term;
        $subjects->years = $request->years;
        $subjects->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Subject updated successfully',
            'subject' => $subjects,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(subjects $subjects)
    {
        $subjects->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Subject deleted successfully',
        ], 200);
    }
}
