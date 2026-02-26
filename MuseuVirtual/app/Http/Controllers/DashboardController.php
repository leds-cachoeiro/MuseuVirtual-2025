<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Rocha;
use App\Models\Mineral;
use App\Models\Jazida;
use App\Models\Fotos;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas
        $stats = [
            'rochas' => Rocha::count(),
            'minerais' => Mineral::count(),
            'jazidas' => Jazida::count(),
            'fotos' => Fotos::count(),
        ];

        // Atividades recentes combinadas
        $atividades = collect();

        $atividades = $atividades->merge(
            Fotos::latest()->take(5)->get()->map(fn($f) => [
                'tipo' => 'fotos',
                'id' => $f->id,
                'imagem' => asset('storage/' . $f->caminho),
                'texto' => "Nova foto adicionada (ID: {$f->id})",
                'sub' => '—'
            ])
        );

        $atividades = $atividades->merge(
            Rocha::latest()->take(5)->get()->map(fn($r) => [
                'tipo' => 'rochas',
                'id' => $r->id,
                'imagem' => '/img/rocha.png', // ou use uma imagem padrão
                'texto' => "Nova rocha registrada: {$r->nome}",
                'sub' => $r->tipo ?? '—'
            ])
        );

        $atividades = $atividades->merge(
            Mineral::latest()->take(5)->get()->map(fn($m) => [
                'tipo' => 'minerais',
                'id' => $m->id,
                'imagem' => '/img/mineral.png',
                'texto' => "Novo mineral registrado: {$m->nome}",
                'sub' => $m->formula ?? '—'
            ])
        );

        $atividades = $atividades->merge(
            Jazida::latest()->take(5)->get()->map(fn($j) => [
                'tipo' => 'jazidas',
                'id' => $j->id,
                'imagem' => '/img/jazida.png',
                'texto' => "Nova jazida adicionada: {$j->localizacao}",
                'sub' => $j->descricao ?? '—'
            ])
        );

        // Ordena todas por data de criação e pega as 8 mais recentes
        $atividades = $atividades->sortByDesc(fn($item) => $item['id'])->take(8)->values();

        $fotosSemLigacao = Fotos::whereNull('idRocha')
                                ->whereNull('idMineral')
                                ->whereNull('idJazida')
                                ->count();

        return Inertia::render('Dashboard', [
            'estatisticas' => $stats,
            'atividades' => $atividades,
            'fotosSemLigacao' => $fotosSemLigacao
        ]);
    }
}