<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the website settings form.
     */
    public function index(): View
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'AngananSoft'),
            'site_tagline' => Setting::get('site_tagline', 'Download software multimedia, tools, game, windows, IDM untuk PC|Android|Iphone. Full Version|Premium.'),
            'site_description' => Setting::get('site_description', 'Download software multimedia, tools, game, windows, IDM untuk PC|Komputer Full Version.'),
            'site_keywords' => Setting::get('site_keywords', 'Download Software PC Komputer, Game, android, IPhone Full Version'),
            'archive_password' => Setting::get('archive_password', 'anganansoft'),
            'contact_email' => Setting::get('contact_email', 'contact@anganansoft.com'),
            'facebook_url' => Setting::get('facebook_url', 'https://facebook.com/anganansoft'),
            'footer_about' => Setting::get('footer_about', 'AngananSoft adalah blog penyedia software multimedia, games, tools windows gratis.'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update website settings with strict validation.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:100',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:255',
            'archive_password' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:150',
            'facebook_url' => 'nullable|url:http,https|max:255',
            'footer_about' => 'nullable|string|max:1000',
        ]);

        foreach ($validated as $field => $value) {
            Setting::set($field, $value);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan website berhasil disimpan!');
    }

    /**
     * Update administrator password securely.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini yang Anda masukkan salah.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Kata sandi akun administrator berhasil diperbarui!');
    }
}
