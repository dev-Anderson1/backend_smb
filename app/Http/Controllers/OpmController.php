<?php

namespace App\Http\Controllers;

use App\Models\Opm;
use Illuminate\Http\Request;

class OpmController extends Controller
{
    public function index()
    {
        return response()->json(Opm::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bpm' => 'required|string|max:255',
        ]);

        $opm = Opm::create($validated);

        return response()->json([
            'message' => 'OPM criada com sucesso!',
            'data' => $opm
        ], 201);
    }

    public function show(Opm $opm)
    {
        return response()->json($opm);
    }

    public function update(Request $request, Opm $opm)
    {
        $validated = $request->validate([
            'bpm' => 'required|string|max:255',
        ]);

        $opm->update($validated);

        return response()->json([
            'message' => 'OPM atualizada com sucesso!',
            'data' => $opm
        ]);
    }

    public function destroy(Opm $opm)
    {
        $opm->delete();

        return response()->json([
            'message' => 'OPM deletada com sucesso!'
        ], 204);
    }
}
