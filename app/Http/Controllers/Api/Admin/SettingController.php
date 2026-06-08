<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::all()->groupBy('group');
        return response()->json($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'settings'             => 'required|array',
            'settings.*.key'       => 'required|string|max:100',
            'settings.*.value'     => 'nullable|string',
            'settings.*.type'      => 'nullable|string|max:30',
            'settings.*.group'     => 'nullable|string|max:50',
            'settings.*.is_public' => 'boolean',
        ]);

        foreach ($request->settings as $item) {
            $values = ['value' => $item['value'] ?? null];

            foreach (['type', 'group', 'is_public'] as $field) {
                if (array_key_exists($field, $item)) {
                    $values[$field] = $item[$field];
                }
            }

            Setting::updateOrCreate(
                ['key' => $item['key']],
                $values
            );
        }

        return response()->json(['message' => 'Pengaturan disimpan.']);
    }
}
