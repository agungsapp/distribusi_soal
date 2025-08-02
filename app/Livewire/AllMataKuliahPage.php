<?php

namespace App\Livewire;

use App\Models\MataKuliah;
use App\Models\Pengampu;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AllMataKuliahPage extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        // Kueri MataKuliah dengan relasi
        $query = MataKuliah::with('pengampu.dosen');

        // Jika user adalah dosen, filter hanya mata kuliah yang diampu
        if ($user->role === 'dosen') {
            $mataKuliahIds = Pengampu::where('dosen_id', $user->id)
                ->pluck('mata_kuliah_id')
                ->toArray();

            // Filter berdasarkan mata kuliah yang diampu
            // Jika tidak ada mata kuliah yang diampu, query akan return empty
            $query->whereIn('id', $mataKuliahIds);
        }
        // Jika admin, tampilkan semua (tidak perlu filter tambahan)

        // Terapkan pencarian jika ada
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('kode', 'like', '%' . $this->search . '%')
                    ->orWhereHas('peminatan.prodi', function ($subQ) {
                        $subQ->where('nama', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Paginate hasil
        $pelajarans = $query->paginate(12);

        // dd($pelajarans);

        return view('livewire.all-mata-kuliah-page', [
            'pelajarans' => $pelajarans,
        ]);
    }
}
