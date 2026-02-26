<?php

namespace App\Http\Controllers;
use App\Models\Eon;
use App\Models\Era;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Jazida;
use App\Models\Mineral;
use App\Models\Rocha;
use App\Models\Aquisicoes;

class TimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eons = Eon::with([
            'eras.aquisicoes.rocha.fotos',
            'eras.aquisicoes.mineral.fotos',
            'eras.periodos.aquisicoes.rocha.fotos',
            'eras.periodos.aquisicoes.mineral.fotos'
        ])->get();

        return Inertia::render('Dashboard/Timeline/Timeline', [
            'eons' => $eons
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Dashboard/Timeline/Create', [
            'rochas' => Rocha::all(),
            'minerais' => Mineral::all(),
            'eons' => Eon::with('eras.periodos')->get(),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'era_id'     => 'nullable|exists:eras,id',
            'periodo_id' => 'nullable|exists:periodos,id',
            'rocha_id'   => 'nullable|exists:rochas,id',
            'mineral_id' => 'nullable|exists:minerals,id',
        ]);

        // Garante que ao menos era ou período esteja preenchido
        if (!$data['era_id'] && !$data['periodo_id']) {
            return back()->withErrors(['error' => 'É necessário informar uma Era ou Período.']);
        }

        // Garante que ao menos rocha ou mineral esteja preenchido
        if (!$data['rocha_id'] && !$data['mineral_id']) {
            return back()->withErrors(['error' => 'É necessário informar uma Rocha ou Mineral.']);
        }
        
        Aquisicoes::create($data);


        return back()->with('success', 'Aquisição associada com sucesso!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Era $era)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Era $era)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
        'era' => 'required|string|max:255',
        'periodo' => 'required|string|max:255',
        'idRocha' => 'nullable|exists:rochas,id',
        'idMineral' => 'nullable|exists:minerals,id'
    ]);

    if ($request->filled('idRocha')) {
        $rocha = Rocha::findOrFail($request->idRocha);
        $rocha->era = $request->era;
        $rocha->periodo = $request->periodo;
        $rocha->save();
    } elseif ($request->filled('idMineral')) {
        $mineral = Mineral::findOrFail($request->idMineral);
        $mineral->era = $request->era;
        $mineral->periodo = $request->periodo;
        $mineral->save();
    }

    return back()->with('success', 'Associação atualizada!');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Era $era)
    {
        //
    }

    public function destroyAssociacao($id)
    {
        // supondo que o modelo seja Associacao ou Aquisicao
        $Aquisicoes = Aquisicoes::findOrFail($id);
        $Aquisicoes->delete();

        return redirect()->back()->with('success', 'Associação removida com sucesso!');
    }

}
