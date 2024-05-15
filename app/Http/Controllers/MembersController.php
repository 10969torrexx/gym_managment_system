<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exercises;
use Illuminate\Support\Facades\Auth;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = User::where('role', 0)->get();
        return view('members.index', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $users = User::where('role', 0)->where('id', $request->id)->update([
            'fitness_level' => $request->fitness_level
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $users = User::where('role', 0)->where('id', $request->id)->delete();
        if($users) {
            return response()->json([
                'status' => 200,
                'message' => 'Member deleted!'
            ]);
        }
        return response()->json([
            'status' => 500,
            'message' => 'Failed to delete Member!'
        ]);
    }
}
