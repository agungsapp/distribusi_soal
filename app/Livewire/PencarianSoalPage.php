<?php

namespace App\Livewire;

use App\Models\Pengampu;
use App\Models\Soal;
use App\Models\MataKuliah;
use App\Models\Periode;
use App\Models\User;
use App\Models\Peminatan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PencarianSoalPage extends Component
{
    use WithPagination;

    public $search = '';
    public $mata_kuliah_id = '';
    public $periode_id = '';
    public $dosen_id = '';
    public $peminatan_id = '';

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMataKuliahId()
    {
        $this->resetPage();
    }

    public function updatingPeriodeId()
    {
        $this->resetPage();
    }

    public function updatingDosenId()
    {
        $this->resetPage();
    }

    public function updatingPeminatanId()
    {
        $this->resetPage();
    }

    // Reset all filters
    public function resetFilters()
    {
        $this->reset(['search', 'mata_kuliah_id', 'periode_id', 'dosen_id', 'peminatan_id']);
        $this->resetPage();
    }

    // Check if any filter is applied
    public function hasFilters()
    {
        return !empty($this->search) ||
            !empty($this->mata_kuliah_id) ||
            !empty($this->periode_id) ||
            !empty($this->dosen_id) ||
            !empty($this->peminatan_id);
    }

    public function render()
    {
        $user = Auth::user();
        $soals = collect();

        // Get filter options
        $mataKuliahs = MataKuliah::orderBy('nama')->get();
        $periodes = Periode::orderBy('tahun_ajaran', 'desc')->get();
        $peminatans = Peminatan::orderBy('nama')->get();

        // Get dosens based on user role
        if ($user->role === 'admin') {
            $dosens = User::where('role', 'dosen')->orderBy('name')->get();
        } else {
            $dosens = collect([$user]); // Only current user for dosen role
        }

        // Only execute query if filters are applied
        if ($this->hasFilters()) {
            $query = Soal::with([
                'pengampu.mataKuliah.peminatan.prodi',
                'pengampu.dosen',
                'pengampu.periode'
            ])
                ->whereHas('pengampu', function ($q) {
                    // Filter by mata kuliah
                    $q->when($this->mata_kuliah_id, function ($query) {
                        $query->where('mata_kuliah_id', $this->mata_kuliah_id);
                    })
                        // Filter by periode
                        ->when($this->periode_id, function ($query) {
                            $query->where('periode_id', $this->periode_id);
                        })
                        // Filter by dosen
                        ->when($this->dosen_id, function ($query) {
                            $query->where('dosen_id', $this->dosen_id);
                        })
                        // Filter by peminatan
                        ->when($this->peminatan_id, function ($query) {
                            $query->whereHas('mataKuliah', function ($q2) {
                                $q2->where('peminatan_id', $this->peminatan_id);
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

            // Filter by current user if dosen role
            if ($user->role === 'dosen') {
                $query->where('pengampus.dosen_id', $user->id);
            }

            $soals = $query->paginate(10);
        }

        return view('livewire.pencarian-soal-page', [
            'soals' => $soals,
            'mataKuliahs' => $mataKuliahs,
            'periodes' => $periodes,
            'dosens' => $dosens,
            'peminatans' => $peminatans,
            'hasFilters' => $this->hasFilters(),
        ]);
    }
}
