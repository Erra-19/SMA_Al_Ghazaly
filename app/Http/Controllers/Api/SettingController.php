<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $settings = Setting::when($request->group, fn ($q) => $q->where('group', $request->group))
            ->get(['key', 'value', 'group'])
            ->keyBy('key')
            ->map(fn ($s) => $s->value);

        return response()->json($settings);
    }
}
