<?php
namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:exercises,name|max:255',
            'body_part' => 'required|string|max:255',
        ]);

        $exercise = Exercise::create($validated);

        return response()->json($exercise);
    }
}