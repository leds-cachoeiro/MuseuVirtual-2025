<?php

namespace App\Http\Controllers;

use App\Models\Rocha;
use App\Models\Jazida;
use App\Models\Mineral;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse; // Para type-hinting de retorno
use Illuminate\Http\JsonResponse; // Para type-hinting de retorno de API
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Certifique-se de ter o pacote instalado

class RochaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Monta query base
        $query = Rocha::with('fotos');

        // Filtra por nome se fornecido via query string `nome`
        if ($request->filled('nome')) {
            $nome = $request->input('nome');
            $query->where('nome', 'like', "%{$nome}%");
        }

        // Paginação mantendo query string para links
        $rochas = $query->paginate(10)->withPath(url()->current());;  // 10 rochas por página

        return Inertia::render('Dashboard/Rochas/Index', [
            'rochas' => $rochas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $jazidas = Jazida::all(['id', 'descricao']);
    $minerais = Mineral::all(['id', 'nome']);

    return Inertia::render('Dashboard/Rochas/Create', [
        'jazidas' => $jazidas,
        'minerais' => $minerais,
    ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $validated = $request->validate([
        'nome' => 'required|string|max:255',
        'descricao' => 'required|string',
        'composicao' => 'required|string',
        'tipo' => 'required|integer',
        'jazida_id' => 'nullable|exists:jazidas,id',
        'minerais_ids' => 'nullable|array',
        'minerais_ids.*' => 'exists:minerals,id',
        'ornamental' => 'required|boolean',

    ]);

    $rocha = Rocha::create($validated);

    // Sincroniza minerais
    $rocha->minerais()->sync($request->input('minerais_ids', []));

    if ($request->hasFile('foto')) {
        $fotosRequest = new Request([
            "idRocha" => $rocha->id,
            "capa_nome" => $request->input('capa_nome'),
        ]);
        $fotosRequest->files->set('foto', $request->file('foto'));
        app(\App\Http\Controllers\FotosController::class)->store($fotosRequest);
    }

    return redirect()->route('rochas.index')->with('success', 'Rocha criada com sucesso!');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $tipo, $rocha)
    {
        $rocha = Rocha::with('fotos.anotacoes', 'minerais')->where('slug', $rocha)->firstOrFail();
        return view('rochaEspecifica', compact('rocha'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
    $rocha = Rocha::with(['fotos','minerais'])->findOrFail($id);
    $jazidas = Jazida::all(['id', 'descricao']);
    $minerais = Mineral::all(['id', 'nome']);

    return Inertia::render('Dashboard/Rochas/Edit', [
        'rocha' => array_merge($rocha->toArray(), [
            'minerais_ids' => $rocha->minerais->pluck('id')->toArray(),
        ]),
        'jazidas' => $jazidas,
        'minerais' => $minerais,
    ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rocha $rocha)
    {
    $request->validate([
        'nome' => 'sometimes|required|string|max:255',
        'descricao' => 'nullable|string',
        'composicao' => 'nullable|string',
        'tipo' => 'required|integer',
        'jazida_id' => 'nullable|exists:jazidas,id',
        'minerais_ids' => 'nullable|array',
        'minerais_ids.*' => 'exists:minerals,id',
        'ornamental' => 'required|boolean',
    ]);

    $rocha->update($request->only(['nome','descricao','composicao','tipo','jazida_id', 'ornamental']));

    $rocha->minerais()->sync($request->input('minerais_ids', []));

    return redirect()->route('rochas.index')->with('success', 'Rocha atualizada com sucesso!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rocha $rocha)
    {
        foreach ($rocha->fotos as $foto) {
            app(\App\Http\Controllers\FotosController::class)->destroy($foto->id);
        }

        $rocha->delete();
        $rochas = Rocha::paginate(10);  // 10 rochas por página

        return redirect()->route('rochas.index', 'rochas')->with('success', 'Rocha deletada com sucesso!');
    }

    public function apiListRocha()
    {
        $rochas = Rocha::all();
        return json_encode($rochas);
    }

    public function site()
    {
        $rochastipo1 = Rocha::where("tipo", 1)->with("fotos")->paginate(12);
        $rochastipo2 = Rocha::where("tipo", 2)->with("fotos")->paginate(12);
        $rochastipo3 = Rocha::where("tipo", 3)->with("fotos")->paginate(12);
        // dd($rochas);
        return view('rochas', compact("rochastipo1", "rochastipo2", "rochastipo3"));
    }

    public function siteOrnamentais()
    {
        $rochas = Rocha::where('ornamental', true)->with('fotos')->paginate(12);
        return view('rochasOrnamentais', compact('rochas'));
    }

    public function gerarQrCode($id)
    {
        $rocha = Rocha::findOrFail($id);
        $url = route('Rocha.show', $rocha->id); // ou use slug se tiver
        $qrCode = QrCode::format('png')->size(300)->generate($url);

        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="rocha_' . $rocha->id . '_qr.png"');
    }
    public function site_tipo_rocha($tipo)
    {
        $rochastipo = Rocha::where('tipo', $tipo)->with('fotos')->get();
        return view('rocha_tipo', compact('rochastipo', 'tipo'));
    }

    public function site_show($id)
    {
        $rocha = Rocha::with('fotos')->findOrFail($id);
        return view('rochaEspecifica', compact('rocha'));
    }
}
