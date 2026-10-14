<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return redirect()->route('connexion');
    }

    public function showRegister()
    {
        return redirect()->route('inscription');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'max:160'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'L\'e-mail ou identifiant est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Compatibilité ancien compte config marina / marina4321
        $this->ensureDefaultAdminExists();

        $login = trim($validated['email']);
        if ($login === config('marina.admin_username', 'marina') || $login === 'marina') {
            $login = 'marina@admin.local';
        }

        if (!Auth::attempt(['email' => $login, 'password' => $validated['password']], $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Identifiants incorrects.']);
        }

        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Ce compte n\'a pas les droits administrateur.']);
        }

        $request->session()->regenerate();
        $request->session()->put('admin_boutique_auth', true);

        return redirect()->route('boutiques.admin');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'e-mail est obligatoire.',
            'email.unique' => 'Cet e-mail est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => User::ROLE_ADMIN,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('admin_boutique_auth', true);

        return redirect()->route('boutiques.admin')
            ->with('success', 'Compte administrateur créé.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('admin_boutique_auth');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Déconnexion réussie.');
    }

    private function ensureDefaultAdminExists(): void
    {
        $email = 'marina@admin.local';
        $password = config('marina.admin_password', 'marina4321');

        if (!User::where('email', $email)->exists()) {
            // Aussi accepter l'ancien login "marina" via cet e-mail dédié
            User::create([
                'name' => config('marina.admin_username', 'marina'),
                'email' => $email,
                'password' => $password,
                'role' => User::ROLE_ADMIN,
            ]);
        }

        // Si l'utilisateur tape encore "marina" comme e-mail invalide, on mappe via name — géré côté vue hint
    }
}
