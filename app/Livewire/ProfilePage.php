<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilePage extends Component
{
    public $name;
    public $email;
    public $nidn;
    public $role;
    public $is_active;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'nidn' => 'nullable|string|max:20|unique:users,nidn',
    ];

    protected $passwordRules = [
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ];

    public function mount()
    {
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nidn = $user->nidn;
        $this->role = $user->role;
        $this->is_active = $user->is_active;
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function updateProfile()
    {
        $user = Auth::user();
        $this->rules['email'] = 'required|email|max:255|unique:users,email,' . $user->id;
        $this->rules['nidn'] = 'nullable|string|max:20|unique:users,nidn,' . ($user->id ?? null);
        $this->validate($this->rules);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'nidn' => $this->nidn,
        ]);

        session()->flash('message', 'Profil berhasil diperbarui.');
        $this->loadUserData(); // Muat ulang data untuk memastikan konsistensi
    }

    public function updatePassword()
    {
        $this->validate($this->passwordRules);

        $user = Auth::user();
        if (!Hash::check($this->current_password, $user->password)) {
            session()->flash('error', 'Password saat ini salah.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        session()->flash('message', 'Password berhasil diperbarui.');
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function render()
    {
        return view('livewire.profile-page');
    }
}
