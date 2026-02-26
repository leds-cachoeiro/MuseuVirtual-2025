<?php

namespace App\Http\Controllers;

use App\Models\Fotos;
use App\Models\Jazida;
use App\Models\Mineral;
use App\Models\Rocha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use App\Models\AnotacaoFoto;

class FotosController extends Controller
{
    public function index(Request $request)
    {
        $query = Fotos::with(['rocha', 'mineral', 'jazida', 'anotacoes']);

        if ($request->filled('termo')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('rocha', fn($r) => $r->where('nome', 'like', "%{$request->termo}%"))
                  ->orWhereHas('mineral', fn($m) => $m->where('nome', 'like', "%{$request->termo}%"))
                  ->orWhereHas('jazida', fn($j) => $j->where('localizacao', 'like', "%{$request->termo}%"));
            });
        }

        if ($request->boolean('semLigacao')) {
            $query->whereNull('idRocha')
                  ->whereNull('idMineral')
                  ->whereNull('idJazida');
        } else {
            if ($request->boolean('comRocha')) {
                $query->whereNotNull('idRocha');
            }

            if ($request->boolean('comMineral')) {
                $query->whereNotNull('idMineral');
            }

            if ($request->boolean('comJazida')) {
                $query->whereNotNull('idJazida');
            }
        }

        return Inertia::render('Dashboard/Fotos/Index', [
            'fotos' => $query->paginate(10)->withQueryString(),
            'rochas' => Rocha::all(),
            'minerais' => Mineral::all(),
            'jazidas' => Jazida::all(),
            'filters' => $request->only([
                'termo', 'comRocha', 'comMineral', 'comJazida', 'semLigacao',
            ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Fotos/Create', [
            'rochas' => Rocha::all(),
            'minerais' => Mineral::all(),
            'jazidas' => Jazida::all(),
            'idRochas' => request('idRocha'),
        ]);
    }

    public function store(Request $request)
    {
        $diretorio = 'fotos/geral';
        $atributos = [];

        if ($request->filled('idRocha')) {
            $atributos['idRocha'] = $request->idRocha;
            $diretorio = 'fotos/rochas';
        } elseif ($request->filled('idMineral')) {
            $atributos['idMineral'] = $request->idMineral;
            $diretorio = 'fotos/minerais';
        } elseif ($request->filled('idJazida')) {
            $atributos['idJazida'] = $request->idJazida;
            $diretorio = 'fotos/jazidas';
        }

        if ($request->hasFile('foto')) {
            $nomeCapa = $request->input('capa_nome');

            foreach ($request->file('foto') as $arquivo) {
                $foto = new Fotos($atributos);

                $nomeOriginal = $arquivo->getClientOriginalName();
                $nomeFinal = time() . "_" . $nomeOriginal;
                $caminho = $arquivo->storeAs($diretorio, $nomeFinal, 'public');
                $foto->caminho = $caminho;

                $marcarCapa = ($nomeOriginal === $nomeCapa) ? 1 : 0;

                if ($marcarCapa) {
                    $existeCapa = Fotos::where('capa', true)
                        ->when(isset($atributos['idRocha']), fn($q) => $q->where('idRocha', $atributos['idRocha']))
                        ->when(isset($atributos['idMineral']), fn($q) => $q->where('idMineral', $atributos['idMineral']))
                        ->when(isset($atributos['idJazida']), fn($q) => $q->where('idJazida', $atributos['idJazida']))
                        ->exists();

                    if ($existeCapa) {
                        return redirect()->back()
                            ->withErrors(['capa' => 'Este item já possui uma foto marcada como capa.'])
                            ->withInput();
                    }
                }

                $foto->capa = $marcarCapa;
                $foto->save();
            }
        }

        if (! in_array($request->tipo, ['1','2','3'])){
            return redirect()->route('fotos.index')->with('success', 'Fotos enviadas com sucesso!');
        }
    }

    public function edit($id)
    {
        $foto = Fotos::with('anotacoes')->findOrFail($id);

        $outraCapaExiste = false;

        if (!$foto->capa) {
            $query = Fotos::where('capa', true)
                ->where('id', '!=', $foto->id);

            $query->where(function ($q) use ($foto) {
                if ($foto->idRocha) {
                    $q->where('idRocha', $foto->idRocha);
                } elseif ($foto->idMineral) {
                    $q->where('idMineral', $foto->idMineral);
                } elseif ($foto->idJazida) {
                    $q->where('idJazida', $foto->idJazida);
                }
            });

            $outraCapaExiste = $query->exists();
        }

        // Buscando as listas completas para popular os selects
        $rochas = Rocha::all();
        $minerais = Mineral::all();
        $jazidas = Jazida::all();

        return Inertia::render('Dashboard/Fotos/Edit', [
            'fotos' => [
                'id' => $foto->id,
                'caminho' => $foto->caminho,
                'anotacoes' => $foto->anotacoes,
                'idRocha' => $foto->idRocha,
                'idMineral' => $foto->idMineral,
                'idJazida' => $foto->idJazida,
                'capa' => (bool) $foto->capa,
                'outraCapaExiste' => $outraCapaExiste,
            ],
            'rochas' => $rochas,
            'minerais' => $minerais,
            'jazidas' => $jazidas,
        ]);
    }

    public function update(Request $request, $id)
    {
        $foto = Fotos::findOrFail($id);
        $capa = $request->capa != null && $request->capa != "0" ? 1 : 0;

        if ($capa) {
            $existeOutraCapa = Fotos::where('capa', true)
                ->where('id', '!=', $foto->id)
                ->when($request->filled('idRocha'), fn($q) => $q->where('idRocha', $request->idRocha))
                ->when($request->filled('idMineral'), fn($q) => $q->where('idMineral', $request->idMineral))
                ->when($request->filled('idJazida'), fn($q) => $q->where('idJazida', $request->idJazida))
                ->exists();

            if ($existeOutraCapa) {
                return redirect()->back()
                    ->withErrors(['capa' => 'Este item já possui uma foto marcada como capa.'])
                    ->withInput();
            }
        }

        $data = [
            'idRocha' => $request->idRocha,
            'idMineral' => $request->idMineral,
            'idJazida' => $request->idJazida,
            'capa' => $capa,
        ];

        if ($request->hasFile('foto')) {
            File::delete('storage/' . $foto->caminho);
            $arquivo = $request->file('foto');
            $nomeFinal = time() . '_' . $arquivo->getClientOriginalName();

            $diretorio = 'fotos/geral';
            if ($request->filled('idRocha')) {
                $diretorio = 'fotos/rochas';
            } elseif ($request->filled('idMineral')) {
                $diretorio = 'fotos/minerais';
            } elseif ($request->filled('idJazida')) {
                $diretorio = 'fotos/jazidas';
            }

            $data['caminho'] = $arquivo->storeAs($diretorio, $nomeFinal, 'public');
        }

        $foto->update($data);

        return redirect()->route('fotos.index')->with('success', 'Foto atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $foto = Fotos::findOrFail($id);
        $caminho = public_path('storage/' . $foto->caminho);

        if (File::exists($caminho)) {
            File::delete($caminho);
        }

        $foto->delete();

        return redirect()->back()->with('success', 'Foto excluída com sucesso!');
    }

    public function salvarAnotacoes(Request $request, $fotoId)
    {
        $anotacoes = $request->input('anotacoes', []);
        $deletadas = $request->input('deletadas', []);

        if (!empty($deletadas)) {
            AnotacaoFoto::whereIn('id', $deletadas)->delete();
        }

        foreach ($anotacoes as $anotacao) {
            if (!empty($anotacao['id'])) {
                $registro = AnotacaoFoto::find($anotacao['id']);
                if ($registro) {
                    $registro->update([
                        'x' => $anotacao['x'],
                        'y' => $anotacao['y'],
                        'texto' => $anotacao['texto'],
                    ]);
                }
            } else {
                AnotacaoFoto::create([
                    'foto_id' => $fotoId,
                    'x' => $anotacao['x'],
                    'y' => $anotacao['y'],
                    'texto' => $anotacao['texto'],
                ]);
            }
        }
    }   
}