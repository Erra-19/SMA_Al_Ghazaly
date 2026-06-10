<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $settings = Setting::when($request->group, fn ($q) => $q->where('group', $request->group))
            ->where('is_public', 1)
            ->get(['key', 'value', 'type', 'group'])
            ->keyBy('key')
            ->map(fn ($s) => $s->value);

        return response()->json($settings);
    }

    public function profile(): JsonResponse
    {
        $rawSettings = Setting::where('is_public', 1)
            ->get(['key', 'value', 'type', 'group']);

        $settings = $rawSettings->groupBy('group');

        // Flat key→value map agar frontend mudah akses per key
        $settingsFlat = $rawSettings->keyBy('key')->map(fn($s) => $s->value);

        $pages = Page::where('is_published', 1)
            ->whereIn('slug', [
                'profil-sekolah',
                'visi-misi',
                'sejarah',
                'fasilitas',
                'ekstrakurikuler',
                'kontak',
            ])
            ->get(['page_id', 'title', 'slug', 'content', 'thumbnail', 'meta_title', 'meta_description'])
            ->keyBy('slug');

        return response()->json([
            'settings'      => $settings,
            'settings_flat' => $settingsFlat,
            'pages'         => $pages,
        ]);
    }
}
