<?php

use App\Http\Controllers\FotosController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\JazidaController;
use App\Http\Controllers\MineralController;
use App\Http\Controllers\EraController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RochaController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimelineController;

// Público:
Route::get("/", [SiteController::class, 'home'])->name("home");
Route::get("/site/jazidas/{id}", [JazidaController::class, 'show'])->name("site.jazidas.show");
Route::get('/site/minerais/{slug_mineral}', [MineralController::class, 'show'])->name('site.minerais.show');
Route::get("/site/rochas/tipo/{tipo}", [RochaController::class, 'site_tipo_rocha'])->name("site.rochas.tipo");
Route::get('/rochas/{id}/qrcode', [RochaController::class, 'gerarQrCode'])->name('rochas.qrcode');
Route::get('/site/rochas/{tipo}/{rocha}', [RochaController::class, 'show'])->name('site.rochas.show');
Route::get("/busca", [SiteController::class, 'busca'])->name("busca");

Route::get("/site/rochas", [RochaController::class, 'site'])->name("site.rochas");
Route::get("/site/minerais", [MineralController::class, 'site'])->name("site.minerais");
Route::get("/site/jazidas", [JazidaController::class, 'site'])->name("site.jazidas");
Route::get("/site/rochasOrnamentais", [RochaController::class, 'siteOrnamentais'])->name("site.rochasOrnamentais");


Route::get("/api/rochas", [RochaController::class, 'apiListRocha']);

// Dashboard:
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard Pública:
Route::get('/dashboardPublica', function () {
    return Inertia::render('DashboardPublica');
})->middleware(['auth', 'verified'])->name('dashboardPublica');

// Perfil:
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Jazidas:
Route::resource('/jazidas', JazidaController::class)->middleware(['auth', 'verified']);
Route::get('/api/jazidas', [JazidaController::class, 'apiListJazidas']);

// Rochas:
Route::resource('rocha', RochaController::class)->names('Rocha');
Route::resource('rochas', RochaController::class)->names('rochas');

// Minerais:
Route::resource('minerais', MineralController::class);

// Timeline:
Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline.index');
Route::post('/timeline', [TimelineController::class, 'store'])->name('timeline.store');
Route::delete('/timeline/associacoes/{id}', [TimelineController::class, 'destroyAssociacao'])->name('timeline.associacoes.destroy');


// QRcode:
Route::get('/jazidas/{id}/qrcode', [JazidaController::class, 'gerarQrCode'])->name('jazidas.qrcode');
Route::get('/rochas/{id}/qrcode', [RochaController::class, 'gerarQrCode'])->name('rochas.qrcode');
Route::get('/minerais/{id}/qrcode', [MineralController::class, 'gerarQrCode'])->name('minerais.qrcode');

// Fotos:
Route::post('/upload', [ImageUploadController::class, 'upload'])->name('image.upload');
Route::get('/image-picker/{type?}', [ImageUploadController::class, 'picker'])->name('image.picker');
Route::prefix('fotos')->group(function() {
    Route::get('/', [FotosController::class, 'index'])->name('fotos-index');
    Route::get('/create', [FotosController::class, 'create'])->name('fotos-create');
    Route::post('/', [FotosController::class, 'store'])->name('fotos-store');
    Route::get('/{id}/edit', [FotosController::class, 'edit'])->name('fotos-edit');
    Route::put('/{id}', [FotosController::class, 'update'])->name('fotos-update');
    Route::delete('/{id}', [FotosController::class, 'destroy'])->name('fotos-destroy');
    // Rota única para salvar/criar/atualizar/deletar todas as anotações
    Route::post('/{foto}/anotacoes', [FotosController::class, 'salvarAnotacoes'])->name('fotos.anotacoes.store');
});

// Falback:
Route::fallback(function() {
    return json_encode("Erro, favor não colocar / como caminho para não gerar conflitos. Obrigado :)");
});

// Admin:
Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/rochas', [RochaController::class,'index'])->name('rochas.index');
    Route::get('/fotos', [FotosController::class,'index'])->name('fotos.index');
    Route::get('/jazidas', [JazidaController::class,'index'])->name('jazidas.index');
    Route::get('/minerais', [MineralController::class,'index'])->name('minerais.index');
    Route::resource('/timeline', TimelineController::class);
});



require __DIR__.'/auth.php';
