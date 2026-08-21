<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settingFields = config('setting_fields');
        $settings = Setting::all()->pluck('val', 'name')->toArray();

        return view('admin.settings.index', compact('settingFields', 'settings'));
    }

    public function store(Request $request)
    {
        $settingFields = config('setting_fields');
        $allRules = [];

        foreach ($settingFields as $group) {
            foreach ($group['elements'] as $field) {
                $allRules[$field['name']] = $field['rules'] ?? 'nullable';
            }
        }

        $validated = $request->validate($allRules);

        foreach ($validated as $key => $val) {
            $field = collect($settingFields)
                ->flatMap(fn ($g) => $g['elements'])
                ->firstWhere('name', $key);

            $type = $field['data'] ?? 'string';
            Setting::add($key, $val, $type);
        }

        return redirect()->route('admin.settings.index')->with('status', 'Settings saved successfully.');
    }
}
