<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Periode;
use Illuminate\Support\Facades\Gate;

class PeriodePage extends Component
{
    use WithPagination;

    public $tahun_ajaran;
    public $semester;
    public $periode_id;
    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'tahun_ajaran' => 'required|string',
        'semester' => 'required|in:ganjil,genap',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->tahun_ajaran = '';
        $this->semester = '';
        $this->periode_id = null;
        $this->isEditing = false;
    }

    public function save()
    {
        if (!Gate::allows('create-data') && !$this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menambah periode.');
            return;
        }
        if (!Gate::allows('update-data') && $this->isEditing) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit periode.');
            return;
        }

        $this->validate();

        if ($this->isEditing) {
            $periode = Periode::findOrFail($this->periode_id);
            $periode->update([
                'tahun_ajaran' => $this->tahun_ajaran,
                'semester' => $this->semester,
            ]);
            session()->flash('message', 'Periode berhasil diperbarui.');
        } else {
            Periode::create([
                'tahun_ajaran' => $this->tahun_ajaran,
                'semester' => $this->semester,
            ]);
            session()->flash('message', 'Periode berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit($id)
    {
        if (!Gate::allows('update-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit periode.');
            return;
        }

        $periode = Periode::findOrFail($id);
        $this->periode_id = $id;
        $this->tahun_ajaran = $periode->tahun_ajaran;
        $this->semester = $periode->semester;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        if (!Gate::allows('delete-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus periode.');
            return;
        }

        $periode = Periode::findOrFail($id);
        if ($periode->pengampu()->count() > 0) {
            session()->flash('error', 'Periode tidak dapat dihapus karena masih terkait dengan pengampu.');
            return;
        }
        $periode->delete();
        session()->flash('message', 'Periode berhasil dihapus.');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!Gate::allows('read-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk melihat periode.');
            return view('livewire.periode-page', [
                'periodes' => collect([]),
            ]);
        }

        return view('livewire.periode-page', [
            'periodes' => Periode::where('tahun_ajaran', 'like', '%' . $this->search . '%')
                ->orWhere('semester', 'like', '%' . $this->search . '%')
                ->paginate(10),
        ]);
    }
}
