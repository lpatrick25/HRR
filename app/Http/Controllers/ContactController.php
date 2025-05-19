<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts.
     */
    public function index()
    {
        try {
            $contact = Contact::first();

            return response()->json($contact, 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve contact: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve contact. Please try again later.',
            ], 500);
        }
        // $response = Contact::all()->map(function ($contact, $index) {
        //     $actionUpdate = '<button onclick="view_contact(' . "'" . $contact->id . "'" . ')" type="button" title="Update" class="btn btn-custon-rounded-three btn-primary"><i class="fa fa-edit"></i></button>';
        //     $actionDelete = '<button onclick="trash_contact(' . "'" . $contact->id . "'" . ')" type="button" title="Delete" class="btn btn-custon-rounded-three btn-danger"><i class="fa fa-trash"></i></button>';
        //     $action = $actionUpdate . $actionDelete;

        //     return [
        //         'count' => $index + 1,
        //         'email' => strtolower($contact->email),
        //         'contact' => $contact->contact,
        //         'address' => ucwords(strtolower($contact->address)),
        //         'created_at' => date('l F j, Y', strtotime($contact->created_at)),
        //         'action' => $action,
        //     ];
        // })->toArray();

        // return response()->json($response);
    }

    /**
     * Store a newly created contact.
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'email' => 'required|email|max:100|unique:contacts,email',
                'contact' => 'required|string|max:15|unique:contacts,contact',
                'address' => 'required|string',
            ], [
                'email.required' => 'Email is required.',
                'email.email' => 'Enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'contact.required' => 'Contact number is required.',
                'contact.unique' => 'This contact number is already in use.',
                'address.required' => 'Address is required.',
            ]);

            // Delete all existing contacts before inserting a new one
            Contact::query()->delete(); // Instead of truncate(), use delete()

            // Start transaction AFTER deleting old contacts
            DB::beginTransaction();

            $validated['contact'] = '+63' . $validated['contact'];

            // Create new contact
            $contact = Contact::create($validated);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Contact successfully stored.',
                'contact' => $contact,
            ], 201);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'valid' => false,
                'msg' => '',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to store contact: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to store contact. Please try again later.',
            ], 500);
        }
    }

    /**
     * Display the specified contact.
     */
    public function show($id)
    {
        try {
            $contact = Contact::findOrFail($id);

            return response()->json($contact, 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve contact: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve contact. Please try again later.',
            ], 500);
        }
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Validation
            $validated = $request->validate([
                'email' => 'required|email|max:100|unique:contacts,email,' . $id,
                'contact' => 'required|string|max:15|unique:contacts,contact,' . $id,
                'address' => 'required|string',
            ], [
                'email.required' => 'Email is required.',
                'email.email' => 'Enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'contact.required' => 'Contact number is required.',
                'contact.unique' => 'This contact number is already in use.',
                'address.required' => 'Address is required.',
            ]);

            // Find and update the contact
            $contact = Contact::findOrFail($id);
            $contact->update($validated);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Contact successfully updated.',
            ], 200);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'valid' => false,
                'msg' => '',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update contact: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update contact. Please try again later.',
            ], 500);
        }
    }

    /**
     * Remove the specified contact.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Contact successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete contact: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to delete contact. Please try again later.',
            ], 500);
        }
    }
}
