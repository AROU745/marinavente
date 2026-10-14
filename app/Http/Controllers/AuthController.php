<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showConnexion()
    {
        if (Auth::check()) {
            return $this->redirectFor(Auth::user());
        }

        return view('auth.connexion', [
            'title' => 'Connexion',
            'metaDescription' => 'Connectez-vous à MARINA.',
            'bodyClass' => 'page-auth',
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:160'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Le nom d\'utilisateur est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $this->ensureDefaultAdminExists();

        $login = trim($validated['username']);
        if (strcasecmp($login, 'marina') === 0) {
            $login = 'marina@admin.local';
        }

        $user = User::where('email', $login)
            ->orWhere('name', $validated['username'])
            ->first();

        if (!$user || !Auth::attempt(
            ['email' => $user->email, 'password' => $validated['password']],
            $request->boolean('remember')
        )) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Nom d\'utilisateur ou mot de passe incorrect.']);
        }

        $request->session()->regenerate();

        if (Auth::user()->isAdmin()) {
            $request->session()->put('admin_boutique_auth', true);
        }

        return $this->redirectFor(Auth::user());
    }

    public function showInscription()
    {
        if (Auth::check()) {
            return $this->redirectFor(Auth::user());
        }

        return view('auth.inscription', [
            'title' => 'Inscription',
            'metaDescription' => 'Créez votre compte MARINA.',
            'bodyClass' => 'page-auth',
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Le nom d\'utilisateur est obligatoire.',
            'email.required' => 'L\'e-mail est obligatoire.',
            'email.unique' => 'Cet e-mail est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => User::ROLE_USER,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard')
            ->with('success', 'Bienvenue ! Votre compte est créé.');
    }

    public function showUserLogin()
    {
        return redirect()->route('connexion');
    }

    public function showUserRegister()
    {
        return redirect()->route('inscription');
    }

    public function loginUser(Request $request)
    {
        return $this->login($request);
    }

    public function registerUser(Request $request)
    {
        return $this->register($request);
    }

    public function dashboard()
    {
        $user = Auth::user();
        if (!$user || $user->isAdmin()) {
            return redirect()->route('connexion');
        }

        return view('auth.user-dashboard', [
            'user' => $user,
            'title' => 'Mon espace',
            'metaDescription' => 'Espace utilisateur MARINA.',
            'bodyClass' => 'page-auth',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnexion réussie.');
    }

    private function redirectFor(User $user)
    {
        return $user->isAdmin()
            ? redirect()->route('boutiques.admin')
            : redirect()->route('user.dashboard');
    }

    private function ensureDefaultAdminExists(): void
    {
        if (!User::where('email', 'marina@admin.local')->exists()) {
            User::create([
                'name' => config('marina.admin_username', 'marina'),
                'email' => 'marina@admin.local',
                'password' => config('marina.admin_password', 'marina4321'),
                'role' => User::ROLE_ADMIN,
            ]);
        }
    }
}
