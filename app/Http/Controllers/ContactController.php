<?php

namespace App\Http\Controllers;

use App\Models\contact;
use Illuminate\Http\Request;
use App\Http\Requests\StorecontactRequest;
use App\Http\Requests\UpdatecontactRequest;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contact = contact::all();
        if(!$contact){
            return response()->json([
                'message' => 'Contact not found.',
                'status' => 404
            ]);
        }
       return response()->json([
        'message' => 'Contact fetched successfully.',
        'contact' => $contact,
        'status' => 200
       ]);
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
            'frist_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
        $contact = contact::create([
            'frist_name' => $request->frist_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'message' => $request->message,
        ]);
        return response()->json([
            'message' => 'Message sent successfully.',
            'data' => $contact
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecontactRequest $request, contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(contact $contact)
    {
        //
    }
    public function DeleteAll()
    {
        $count = Contact::count();

        if ($count === 0) {
            return response()->json([
                'message' => 'No contacts to delete.',
                'status' => 404
            ]);
        }

        Contact::truncate();

        return response()->json([
            'message' => 'All contacts deleted successfully.',
            'status' => 200
        ]);
    }
}
