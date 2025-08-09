<?php

namespace App\Livewire;

use App\Models\Pengampu;
use App\Models\Soal;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AllMataKuliahPage extends Component
{
    use WithPagination;

    public $search = '';
    public $nama_mk = '';
    public $kode_mk = '';
    public $periode = '';
    public $dosen = '';
    public $peminatan = '';

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingNamaMk()
    {
        $this->resetPage();
    }

    public function updatingKodeMk()
    {
        $this->resetPage();
    }

    public function updatingPeriode()
    {
        $this->resetPage();
    }

    public function updatingDosen()
    {
        $this->resetPage();
    }

    public function updatingPeminatan()
    {
        $this->resetPage();
    }

    // Reset all filters
    public function resetFilters()
    {
        $this->reset(['search', 'nama_mk', 'kode_mk', 'periode', 'dosen', 'peminatan']);
        $this->resetPage();
    }

    public function render()
    {

        $user = Auth::user();
        // Query soals instead of pengampus to get individual exam records
        $query = Soal::with([
            'pengampu.mataKuliah.peminatan.prodi',
            'pengampu.dosen',
            'pengampu.periode'
        ])
            ->whereHas('pengampu', function ($q) {
                // Filter by mata kuliah name
                $q->when($this->nama_mk, function ($query) {
                    $query->whereHas('mataKuliah', function ($q2) {
                        $q2->where('nama', 'like', '%' . $this->nama_mk . '%');
                    });
                })
                    // Filter by mata kuliah code
                    ->when($this->kode_mk, function ($query) {
                        $query->whereHas('mataKuliah', function ($q2) {
                            $q2->where('kode', 'like', '%' . $this->kode_mk . '%');
                        });
                    })
                    // Filter by periode
                    ->when($this->periode, function ($query) {
                        $query->whereHas('periode', function ($q2) {
                            $q2->where('tahun_ajaran', 'like', '%' . $this->periode . '%');
                        });
                    })
                    // Filter by dosen
                    ->when($this->dosen, function ($query) {
                        $query->whereHas('dosen', function ($q2) {
                            $q2->where('name', 'like', '%' . $this->dosen . '%');
                        });
                    })
                    // Filter by peminatan
                    ->when($this->peminatan, function ($query) {
                        $query->whereHas('mataKuliah.peminatan', function ($q2) {
                            $q2->where('nama', 'like', '%' . $this->peminatan . '%');
                        });
                    });
            })
            // Global search functionality
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // Search in soal path/filename
                    $q->where('path', 'like', '%' . $this->search . '%')
                        // Search in mata kuliah
                        ->orWhereHas('pengampu.mataKuliah', function ($q2) {
                            $q2->where('nama', 'like', '%' . $this->search . '%')
                                ->orWhere('kode', 'like', '%' . $this->search . '%');
                        })
                        // Search in dosen
                        ->orWhereHas('pengampu.dosen', function ($q2) {
                            $q2->where('name', 'like', '%' . $this->search . '%');
                        })
                        // Search in peminatan
                        ->orWhereHas('pengampu.mataKuliah.peminatan', function ($q2) {
                            $q2->where('nama', 'like', '%' . $this->search . '%');
                        })
                        // Search in periode
                        ->orWhereHas('pengampu.periode', function ($q2) {
                            $q2->where('tahun_ajaran', 'like', '%' . $this->search . '%');
                        });
                });
            })
            // Order by mata kuliah name and then by periode
            ->join('pengampus', 'soals.pengampu_id', '=', 'pengampus.id')
            ->join('mata_kuliahs', 'pengampus.mata_kuliah_id', '=', 'mata_kuliahs.id')
            ->join('periodes', 'pengampus.periode_id', '=', 'periodes.id')
            ->orderBy('mata_kuliahs.nama', 'asc')
            ->orderBy('periodes.tahun_ajaran', 'desc')
            ->select('soals.*'); // Select only soals columns to avoid conflicts

        if ($user->role === 'dosen') {
            $query->where('pengampus.dosen_id', $user->id);
        }

        $soals = $query->paginate(10);

        return view('livewire.all-mata-kuliah-page', [
            'soals' => $soals,
        ]);
    }
}
