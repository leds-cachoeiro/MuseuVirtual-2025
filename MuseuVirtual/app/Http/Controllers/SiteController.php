<?php

namespace App\Http\Controllers;

use App\Models\Fotos;
use App\Models\Jazida;
use App\Models\Mineral;
use App\Models\Rocha;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        $fotosRecentes = Fotos::with(['rocha', 'mineral', 'jazida'])
            ->whereNotNull('idRocha')
            ->orWhereNotNull('idMineral')
            ->orWhereNotNull('idJazida')
            ->latest()
            ->take(6) // Exemplo: Pega as 6 fotos mais recentes
            ->get();



        return view("home", compact('fotosRecentes'));
    }
    public function busca(Request $request)
    {
        $termo = trim($request->query('q')); 
        $porPagina = 10;

        // Evita consultas desnecessárias se o termo estiver vazio
        if (empty($termo)) {
            return view('home', [
                'minerais' => collect(),
                'rochas' => collect(),
                'jazidas' => collect(),
                'termo' => $termo,
            ]);
        }

        // Busca com paginação nomeada para evitar conflitos
        $minerais = Mineral::where('nome', 'LIKE', "%{$termo}%")
            ->paginate($porPagina, ['*'], 'minerais')->withPath(url()->current());

        $rochas = Rocha::where('nome', 'LIKE', "%{$termo}%")
            ->paginate($porPagina, ['*'], 'rochas')->withPath(url()->current());

        $jazidas = Jazida::where('localizacao', 'LIKE', "%{$termo}%")
            ->paginate($porPagina, ['*'], 'jazidas')->withPath(url()->current());

        // $rochas_ornamentais = Rocha::where('ornamental', '=', "%{$termo}%")
        //     ->paginate($porPagina, ['*'], 'jazidas')->withPath(url()->current());

        return view('home', compact('minerais', 'rochas', 'jazidas', 'termo'));
    }
}