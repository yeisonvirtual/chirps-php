<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        // 'with' evita el problema n+1 (por cada chirp hace una consulta para obtener el usuario)
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate(([
            'message' => ['required', 'min:3', 'max:255']
        ]));

        // accede al usuario autenticado, luego accede a sus chirps y crea uno nuevo
        auth()->user()->chirps()->create($validated);

        return to_route('chirps.index')
            ->with('status', 'Chirp created sucessfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {   
        // se da autorizacion
        // ejecuta el metodo update del ChirpPolicy
        $this->authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        // se da autorizacion
        // ejecuta el metodo update del ChirpPolicy
        $this->authorize('update', $chirp);

        $validated = $request->validate(([
            'message' => ['required', 'min:3', 'max:255']
        ]));

        $chirp->update($validated);

        return to_route('chirps.index')
            ->with('status', 'Chirp updated sucessfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return to_route('chirps.index')
            ->with('status', 'Chirp deleted sucessfully!');
    }
}
