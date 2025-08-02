<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Peminatan;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PeminatanPage extends Component
{
    use WithPagination;

    public $nama;
    // public $prodi_id;
    public $peminatan_id;
    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'nama' => 'required|string|max:255',
        // 'prodi_id' => 'required|exists:prodis,id',
    ];

    public function mount()
    {

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nama = '';
        // $this->prodi_id = '';
        $this->peminatan_id = null;
        $this->isEditing = false;
    }

    public function save()
    {
        if (!Gate::allows('create-data') && !$this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menambah peminatan.');
            return;
        }
        if (!Gate::allows('update-data') && $this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit peminatan.');
            return;
        }

        $this->validate();

        if ($this->isEditing) {
            $peminatan = Peminatan::findOrFail($this->peminatan_id);
            $peminatan->update([
                'nama' => $this->nama,
                'prodi_id' => 1,
            ]);
            session()->flash('message', 'Peminatan berhasil diperbarui.');
        } else {
            Peminatan::create([
                'nama' => $this->nama,
                'prodi_id' => 1,
            ]);
            session()->flash('message', 'Peminatan berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit($id)
    {
        if (!Gate::allows('update-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit peminatan.');
            return;
        }

        $peminatan = Peminatan::findOrFail($id);
        $this->peminatan_id = $id;
        $this->nama = $peminatan->nama;
        // $this->prodi_id = $peminatan->prodi_id;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        if (!Gate::allows('delete-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus peminatan.');
            return;
        }

        $peminatan = Peminatan::findOrFail($id);
        if ($peminatan->mataKuliah()->count() > 0) {
            session()->flash('error', 'Peminatan tidak dapat dihapus karena masih terkait dengan mata kuliah.');
            return;
        }
        $peminatan->delete();
        session()->flash('message', 'Peminatan berhasil dihapus.');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!Gate::allows('read-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk melihat peminatan.');
            return view('livewire.peminatan-page', [
                'peminatans' => collect([]),
                'prodis' => collect([]),
            ]);
        }

        return view('livewire.peminatan-page', [
            'peminatans' => Peminatan::with('prodi')
                ->where('nama', 'like', '%' . $this->search . '%')
                ->orWhereHas('prodi', fn($query) => $query->where('nama', 'like', '%' . $this->search . '%'))
                ->paginate(10),
            'prodis' => Prodi::all(),
        ]);
    }
}
