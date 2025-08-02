<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pengampu;
use App\Models\MataKuliah;
use App\Models\User;
use App\Models\Periode;
use Illuminate\Support\Facades\Gate;

class PengampuPage extends Component
{
    use WithPagination;

    public $mata_kuliah_id;
    public $dosen_id;
    public $periode_id;
    public $pengampu_id;
    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
        'dosen_id' => 'required|exists:users,id',
        'periode_id' => 'required|exists:periodes,id',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->mata_kuliah_id = '';
        $this->dosen_id = '';
        $this->periode_id = '';
        $this->pengampu_id = null;
        $this->isEditing = false;
    }

    public function save()
    {
        if (!Gate::allows('create-data') && !$this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menambah pengampu.');
            return;
        }
        if (!Gate::allows('update-data') && $this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit pengampu.');
            return;
        }

        $this->validate();

        if ($this->isEditing) {
            $pengampu = Pengampu::findOrFail($this->pengampu_id);
            $pengampu->update([
                'mata_kuliah_id' => $this->mata_kuliah_id,
                'dosen_id' => $this->dosen_id,
                'periode_id' => $this->periode_id,
            ]);
            session()->flash('message', 'Pengampu berhasil diperbarui.');
        } else {
            Pengampu::create([
                'mata_kuliah_id' => $this->mata_kuliah_id,
                'dosen_id' => $this->dosen_id,
                'periode_id' => $this->periode_id,
            ]);
            session()->flash('message', 'Pengampu berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit($id)
    {
        if (!Gate::allows('update-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit pengampu.');
            return;
        }

        $pengampu = Pengampu::findOrFail($id);
        $this->pengampu_id = $id;
        $this->mata_kuliah_id = $pengampu->mata_kuliah_id;
        $this->dosen_id = $pengampu->dosen_id;
        $this->periode_id = $pengampu->periode_id;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        if (!Gate::allows('delete-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus pengampu.');
            return;
        }

        $pengampu = Pengampu::findOrFail($id);
        if ($pengampu->soals()->count() > 0) {
            session()->flash('error', 'Pengampu tidak dapat dihapus karena masih terkait dengan soal.');
            return;
        }
        $pengampu->delete();
        session()->flash('message', 'Pengampu berhasil dihapus.');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!Gate::allows('read-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk melihat pengampu.');
            return view('livewire.pengampu-page', [
                'pengampus' => collect([]),
                'mataKuliahs' => collect([]),
                'dosens' => collect([]),
                'periodes' => collect([]),
            ]);
        }

        return view('livewire.pengampu-page', [
            'pengampus' => Pengampu::with(['mataKuliah.peminatan.prodi', 'dosen', 'periode'])
                ->whereHas('mataKuliah', fn($query) => $query->where('nama', 'like', '%' . $this->search . '%')->orWhere('kode', 'like', '%' . $this->search . '%'))
                ->orWhereHas('dosen', fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
                ->orWhereHas('periode', fn($query) => $query->where('tahun_ajaran', 'like', '%' . $this->search . '%')->orWhere('semester', 'like', '%' . $this->search . '%'))
                ->orWhereHas('mataKuliah.peminatan.prodi', fn($query) => $query->where('nama', 'like', '%' . $this->search . '%'))
                ->paginate(10),
            'mataKuliahs' => MataKuliah::with('peminatan.prodi')->get(),
            'dosens' => User::where('role', 'dosen')->get(),
            'periodes' => Periode::all(),
        ]);
    }
}
