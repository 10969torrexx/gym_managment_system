<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exercises;
use Illuminate\Support\Facades\Auth;

class ExercisesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercises = Exercises::get();
        return view('exercises.index', compact('exercises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'exercise_name' => 'required|min:4',
            'fitness_level' => 'required'
        ]);

        $response = Exercises::create([
            'exercise_name' => $request->exercise_name,
            'fitness_level' => $request->fitness_level
        ]);

        return redirect()->back();
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
        $exercises = Exercises::where('id', $request->id)->update([
            'fitness_level' => $request->fitness_level
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Exercises updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $exercises = Exercises::where('id', $request->id)->delete();
        if($exercises) {
            return response()->json([
                'status' => 200,
                'message' => 'Exercises deleted!'
            ]);
        }
        return response()->json([
            'status' => 500,
            'message' => 'Failed to delete exercises!'
        ]);
    }
}
