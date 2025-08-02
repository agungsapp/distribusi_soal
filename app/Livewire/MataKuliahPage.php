<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MataKuliah;
use App\Models\Peminatan;
use Illuminate\Support\Facades\Gate;

class MataKuliahPage extends Component
{
    use WithPagination;

    public $nama;
    public $peminatan_id;
    public $mata_kuliah_id;
    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'nama' => 'required|string|max:255',
        'peminatan_id' => 'required|exists:peminatans,id',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nama = '';
        $this->peminatan_id = '';
        $this->mata_kuliah_id = null;
        $this->isEditing = false;
    }

    public function save()
    {
        if (!Gate::allows('create-data') && !$this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menambah mata kuliah.');
            return;
        }
        if (!Gate::allows('update-data') && $this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit mata kuliah.');
            return;
        }

        $this->validate();

        if ($this->isEditing) {
            $mataKuliah = MataKuliah::findOrFail($this->mata_kuliah_id);
            $mataKuliah->update([
                'nama' => $this->nama,
                'peminatan_id' => $this->peminatan_id,
                'kode' => MataKuliah::generateKode($this->peminatan_id, $this->mata_kuliah_id),
            ]);
            session()->flash('message', 'Mata kuliah berhasil diperbarui.');
        } else {
            $mataKuliah = MataKuliah::create([
                'nama' => $this->nama,
                'peminatan_id' => $this->peminatan_id,
                'kode' => 'TEMP', // Placeholder, akan diperbarui setelah insert
            ]);
            $mataKuliah->update([
                'kode' => MataKuliah::generateKode($this->peminatan_id, $mataKuliah->id),
            ]);
            session()->flash('message', 'Mata kuliah berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit($id)
    {
        if (!Gate::allows('update-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit mata kuliah.');
            return;
        }

        $mataKuliah = MataKuliah::findOrFail($id);
        $this->mata_kuliah_id = $id;
        $this->nama = $mataKuliah->nama;
        $this->peminatan_id = $mataKuliah->peminatan_id;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        if (!Gate::allows('delete-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus mata kuliah.');
            return;
        }

        $mataKuliah = MataKuliah::findOrFail($id);
        if ($mataKuliah->pengampu()->count() > 0) {
            session()->flash('error', 'Mata kuliah tidak dapat dihapus karena masih terkait dengan pengampu.');
            return;
        }
        $mataKuliah->delete();
        session()->flash('message', 'Mata kuliah berhasil dihapus.');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!Gate::allows('read-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk melihat mata kuliah.');
            return view('livewire.mata-kuliah-page', [
                'mataKuliahs' => collect([]),
                'peminatans' => collect([]),
            ]);
        }

        return view('livewire.mata-kuliah-page', [
            'mataKuliahs' => MataKuliah::with('peminatan.prodi')
                ->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('kode', 'like', '%' . $this->search . '%')
                ->orWhereHas('peminatan.prodi', fn($query) => $query->where('nama', 'like', '%' . $this->search . '%'))
                ->paginate(10),
            'peminatans' => Peminatan::with('prodi')->get(),
        ]);
    }
}
