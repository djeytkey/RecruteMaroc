<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = SystemSetting::get();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'system_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,gif,webp,svg|max:2048',
            'favicon' => 'nullable|file|mimes:ico,png,gif,jpeg|max:512',
        ], [
            'logo.image' => 'Le logo doit être une image (JPEG, PNG, GIF, WebP ou SVG).',
            'favicon.file' => 'Le favicon doit être un fichier image (ICO, PNG ou GIF).',
        ]);

        $settings = SystemSetting::get();

        if ($request->has('system_name')) {
            $settings->system_name = $data['system_name'];
        }

        if ($request->hasFile('logo')) {
            if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $path = $request->file('logo')->store('settings', 'public');
            $settings->logo_path = $path;
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path && Storage::disk('public')->exists($settings->favicon_path)) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $path = $request->file('favicon')->store('settings', 'public');
            $settings->favicon_path = $path;
        }

        $settings->save();

        return redirect()->route('admin.settings.index')->with('success', 'Paramètres enregistrés.');
    }
}
