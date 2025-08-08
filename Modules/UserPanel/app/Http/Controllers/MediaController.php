<?php

namespace Modules\UserPanel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\UserPanel\Models\Media;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query()->orderByDesc('id');
        if ($search = $request->get('search')) {
            $query->where('original_name', 'like', "%{$search}%")
                  ->orWhere('filename', 'like', "%{$search}%");
        }
        $items = $query->paginate(24);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        // Support both single and multiple uploads
        if ($request->hasFile('files')) {
            $request->validate([
                'files' => ['required', 'array'],
                'files.*' => ['file', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            ]);

            $disk = 'public';
            $created = [];
            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads/images', $disk);
                $url = Storage::disk($disk)->url($path);

                $media = Media::create([
                    'filename' => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'disk' => $disk,
                    'path' => $path,
                    'url' => $url,
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'user_id' => auth()->id(),
                ]);

                try {
                    [$width, $height] = getimagesize($file->getPathname());
                    $media->update(['width' => $width, 'height' => $height]);
                } catch (\Throwable $e) { /* ignore */ }

                $created[] = $media;
            }
            return response()->json(['data' => $created], 201);
        }

        // Single upload fallback
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        $file = $request->file('file');
        $disk = 'public';
        $path = $file->store('uploads/images', $disk);
        $url = Storage::disk($disk)->url($path);

        $media = Media::create([
            'filename' => basename($path),
            'original_name' => $file->getClientOriginalName(),
            'disk' => $disk,
            'path' => $path,
            'url' => $url,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'user_id' => auth()->id(),
        ]);

        try {
            [$width, $height] = getimagesize($file->getPathname());
            $media->update(['width' => $width, 'height' => $height]);
        } catch (\Throwable $e) { /* ignore */ }

        return response()->json($media, 201);
    }

    public function destroy(Media $media)
    {
        try {
            Storage::disk($media->disk)->delete($media->path);
        } catch (\Throwable $e) {
            // ignore storage delete failures
        }
        $media->delete();
        return response()->json(['success' => true]);
    }
}


