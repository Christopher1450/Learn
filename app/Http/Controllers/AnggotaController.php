<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;

class AnggotaController extends Controller
{
    public function index()
    {
        return response()->json(Anggota::all(), 200);
    }

    public function store(Request $request)
    {
        $anggota = Anggota::create($request->all());
        return response()->json($anggota, 201);
    }

    public function show($id)
    {
        return response()->json(Anggota::findOrFail($id), 200);
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->update($request->all());
        return response()->json($anggota, 200);
    }

    public function destroy($id)
    {
        Anggota::destroy($id);
        return response()->json(['message' => 'Anggota dihapus'], 200);
    }
}
