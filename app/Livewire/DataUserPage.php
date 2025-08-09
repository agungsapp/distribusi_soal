<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class DataUserPage extends Component
{
    use WithPagination;

    public $name;
    public $email;
    public $nidn;
    public $role = 'dosen';
    public $is_active = false;
    public $user_id;
    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'nidn' => 'nullable|string|max:20|unique:users,nidn',
        'role' => 'required|in:admin,dosen',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->nidn = '';
        $this->role = 'dosen';
        $this->is_active = false;
        $this->user_id = null;
        $this->isEditing = false;
    }

    public function save()
    {
        if (!Gate::allows('create-data') && !$this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menambah user.');
            return;
        }
        if (!Gate::allows('update-data') && $this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit user.');
            return;
        }

        // Update aturan validasi untuk edit (mengabaikan email dan nidn user saat ini)
        if ($this->isEditing) {
            $this->rules['email'] = 'required|email|max:255|unique:users,email,' . $this->user_id;
            $this->rules['nidn'] = 'nullable|string|max:20|unique:users,nidn,' . $this->user_id;
        }

        $this->validate();

        if ($this->isEditing) {
            $user = User::findOrFail($this->user_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'nidn' => $this->nidn,
                'role' => $this->role,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'User berhasil diperbarui.');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'nidn' => $this->nidn,
                'role' => $this->role,
                'is_active' => $this->is_active,
                'password' => Hash::make($this->role === 'admin' ? 'admin123' : 'dosen123'),
            ]);
            session()->flash('message', 'User berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit($id)
    {
        if (!Gate::allows('update-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit user.');
            return;
        }

        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nidn = $user->nidn;
        $this->role = $user->role;
        $this->is_active = $user->is_active;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        if (!Gate::allows('delete-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus user.');
            return;
        }

        $user = User::findOrFail($id);
        // Cek apakah user terkait dengan pengampu atau data lain
        if ($user->pengampu()->count() > 0) {
            session()->flash('error', 'User tidak dapat dihapus karena masih terkait dengan pengampu.');
            return;
        }
        $user->delete();
        session()->flash('message', 'User berhasil dihapus.');
        $this->resetPage();
    }

    public function resetPassword($id)
    {
        if (!Gate::allows('update-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mereset password.');
            return;
        }

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($user->role === 'admin' ? 'admin123' : 'dosen123'),
        ]);
        session()->flash('message', 'Password user berhasil direset.');
    }

    public function updateStatus($id, $status)
    {
        if (!Gate::allows('update-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengubah status user.');
            return;
        }

        $user = User::findOrFail($id);
        $user->update([
            'is_active' => $status,
        ]);
        session()->flash('message', 'Status user berhasil diperbarui.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!Gate::allows('read-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk melihat data user.');
            return view('livewire.data-user-page', [
                'users' => collect([]),
            ]);
        }

        return view('livewire.data-user-page', [
            'users' => User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('nidn', 'like', '%' . $this->search . '%')
                ->paginate(10),
        ]);
    }
}
