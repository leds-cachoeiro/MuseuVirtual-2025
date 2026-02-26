<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');
            $url = asset('storage/' . $path);
            return response()->json(['location' => $url]);
        }
        return response()->json(['error' => 'Nenhum arquivo enviado'], 400);
    }

    public function picker($type)
    {
        if($type == "rocha"){
            $files_set = Storage::disk('public')->files('fotos/rochas');
        }elseif($type == "jazida"){
            $files_set = Storage::disk('public')->files('fotos/jazidas');
        }else{
            $files_set = Storage::disk('public')->files('fotos/minerais');
        }
        $files = Storage::disk('public')->files('uploads');
        $files = array_merge($files_set, $files);
        // Gere URLs públicas
        $images = array_map(function ($file) {
            return asset('storage/' . $file);
        }, $files);

        // Retorne uma página Inertia
        return inertia('ImagePicker', [
            'images' => $images
        ]);
    }
}
