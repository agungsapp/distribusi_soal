<?php

namespace App\Livewire;

use App\Models\MataKuliah;
use App\Models\Pengampu;
use App\Models\Periode;
use App\Models\Soal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateSoalPage extends Component
{
    use WithFileUploads;

    public $mata_kuliah_id = '';
    public $dosen_id = '';
    public $periode_id = '';
    public $file;
    public $status = 'publish';

    // User info
    public $user;
    public $isAdmin = false;

    // For searching pengampu
    public $search_mk = '';
    public $search_dosen = '';
    public $search_periode = '';

    // Available options
    public $mata_kuliahs = [];
    public $dosens = [];
    public $periodes = [];
    public $pengampus = [];

    protected function rules()
    {
        $rules = [
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'periode_id' => 'required|exists:periodes,id',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'status' => 'required|in:draft,publish',
        ];

        // Only admin can select dosen
        if ($this->isAdmin) {
            $rules['dosen_id'] = 'required|exists:users,id';
        }

        return $rules;
    }

    protected function messages()
    {
        $messages = [
            'mata_kuliah_id.required' => 'Mata kuliah harus dipilih.',
            'mata_kuliah_id.exists' => 'Mata kuliah yang dipilih tidak valid.',
            'periode_id.required' => 'Periode harus dipilih.',
            'periode_id.exists' => 'Periode yang dipilih tidak valid.',
            'file.required' => 'File soal harus diunggah.',
            'file.file' => 'File yang diunggah tidak valid.',
            'file.mimes' => 'File harus berformat PDF, DOC, atau DOCX.',
            'file.max' => 'Ukuran file maksimal 10MB.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
        ];

        if ($this->isAdmin) {
            $messages['dosen_id.required'] = 'Dosen harus dipilih.';
            $messages['dosen_id.exists'] = 'Dosen yang dipilih tidak valid.';
        }

        return $messages;
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->isAdmin = $this->user->role === 'admin';

        // If user is dosen, set dosen_id automatically
        if (!$this->isAdmin) {
            $this->dosen_id = $this->user->id;
        }

        $this->loadInitialData();
    }

    public function loadInitialData()
    {
        if ($this->isAdmin) {
            // Admin can see all mata kuliah and all dosens
            $this->mata_kuliahs = MataKuliah::with('peminatan')->orderBy('nama')->get();
            $this->dosens = User::where('role', 'dosen')->orderBy('name')->get();
        } else {
            // Dosen only sees mata kuliah they teach
            $this->mata_kuliahs = MataKuliah::with('peminatan')
                ->whereHas('pengampu', function ($query) {
                    $query->where('dosen_id', $this->user->id);
                })
                ->orderBy('nama')
                ->get();
        }

        $this->periodes = Periode::orderBy('tahun_ajaran', 'desc')->get();
    }

    public function updatedMataKuliahId()
    {
        $this->updatePengampuOptions();
    }

    public function updatedDosenId()
    {
        $this->updatePengampuOptions();
    }

    public function updatedPeriodeId()
    {
        $this->updatePengampuOptions();
    }

    public function updatePengampuOptions()
    {
        if ($this->mata_kuliah_id && $this->dosen_id && $this->periode_id) {
            $query = Pengampu::with(['mataKuliah', 'dosen', 'periode'])
                ->where('mata_kuliah_id', $this->mata_kuliah_id)
                ->where('periode_id', $this->periode_id);

            // For admin, check specific dosen, for dosen use current user
            $dosenId = $this->isAdmin ? $this->dosen_id : $this->user->id;
            $query->where('dosen_id', $dosenId);

            $this->pengampus = $query->get();
        } else {
            $this->pengampus = collect();
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // Use selected dosen_id for admin, or current user for dosen
            $selectedDosenId = $this->isAdmin ? $this->dosen_id : $this->user->id;

            // Check if pengampu exists
            $pengampu = Pengampu::where('mata_kuliah_id', $this->mata_kuliah_id)
                ->where('dosen_id', $selectedDosenId)
                ->where('periode_id', $this->periode_id)
                ->first();

            if (!$pengampu) {
                // Create pengampu if not exists
                $pengampu = Pengampu::create([
                    'mata_kuliah_id' => $this->mata_kuliah_id,
                    'dosen_id' => $selectedDosenId,
                    'periode_id' => $this->periode_id,
                ]);
            }

            // Store file
            $filename = time() . '_' . $this->file->getClientOriginalName();
            $path = $this->file->storeAs('soals', $filename, 'public');

            // Create soal
            Soal::create([
                'pengampu_id' => $pengampu->id,
                'dosen_id' => $selectedDosenId,
                'path' => $path,
                'status' => $this->status,
            ]);

            session()->flash('success', 'Soal berhasil ditambahkan!');
            return redirect()->route('all-mata-kuliah');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('all-mata-kuliah');
    }

    public function render()
    {
        return view('livewire.create-soal-page');
    }
}
