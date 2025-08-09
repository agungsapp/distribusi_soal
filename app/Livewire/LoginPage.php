<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

#[Layout('layouts.auth')]
class LoginPage extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                // Logout pengguna
                Auth::logout();
                // Hapus sesi
                Session::flush();
                // Flash pesan error
                session()->flash('error', 'Akun anda belum aktif silahkan hubungi admin.');
                // Arahkan ke halaman login
                return redirect()->back();
            }

            return redirect()->route('admin.dashboard');

            session()->flash('message', 'Login berhasil.');
        } else {
            session()->flash('error', 'Email atau password salah.');
        }
    }

    public function render()
    {
        return view('livewire.login-page');
    }
}
