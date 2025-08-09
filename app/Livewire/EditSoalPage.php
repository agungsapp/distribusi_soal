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

class EditSoalPage extends Component
{
    use WithFileUploads;

    public $soal;
    public $mata_kuliah_id = '';
    public $dosen_id = '';
    public $periode_id = '';
    public $file;
    public $status = '';
    public $current_file = '';

    // User info
    public $user;
    public $isAdmin = false;

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
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
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

    public function mount(Soal $soal)
    {
        $this->user = Auth::user();
        $this->isAdmin = $this->user->role === 'admin';

        $this->soal = $soal->load(['pengampu.mataKuliah', 'pengampu.dosen', 'pengampu.periode']);

        // Check if dosen can edit this soal (only their own soal)
        if (!$this->isAdmin && $this->soal->dosen_id !== $this->user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit soal ini.');
        }

        // Set initial values
        $this->mata_kuliah_id = $this->soal->pengampu->mata_kuliah_id;
        $this->dosen_id = $this->soal->pengampu->dosen_id;
        $this->periode_id = $this->soal->pengampu->periode_id;
        $this->status = $this->soal->status;
        $this->current_file = $this->soal->path;

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

    public function update()
    {
        $this->validate();

        try {
            // Use selected dosen_id for admin, or current user for dosen
            $selectedDosenId = $this->isAdmin ? $this->dosen_id : $this->user->id;

            // Check if pengampu combination changed
            $currentPengampu = $this->soal->pengampu;
            $needNewPengampu = $currentPengampu->mata_kuliah_id != $this->mata_kuliah_id ||
                $currentPengampu->dosen_id != $selectedDosenId ||
                $currentPengampu->periode_id != $this->periode_id;

            if ($needNewPengampu) {
                // Find or create new pengampu
                $pengampu = Pengampu::firstOrCreate([
                    'mata_kuliah_id' => $this->mata_kuliah_id,
                    'dosen_id' => $selectedDosenId,
                    'periode_id' => $this->periode_id,
                ]);
            } else {
                $pengampu = $currentPengampu;
            }

            $updateData = [
                'pengampu_id' => $pengampu->id,
                'dosen_id' => $selectedDosenId,
                'status' => $this->status,
            ];

            // Handle file upload if new file is provided
            if ($this->file) {
                // Delete old file if exists
                if ($this->soal->path && Storage::disk('public')->exists($this->soal->path)) {
                    Storage::disk('public')->delete($this->soal->path);
                }

                // Store new file
                $filename = time() . '_' . $this->file->getClientOriginalName();
                $path = $this->file->storeAs('soals', $filename, 'public');
                $updateData['path'] = $path;
            }

            // Update soal
            $this->soal->update($updateData);

            session()->flash('success', 'Soal berhasil diperbarui!');
            return redirect()->route('all-mata-kuliah');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            // Additional check for dosen - they can only delete their own soal
            if (!$this->isAdmin && $this->soal->dosen_id !== $this->user->id) {
                session()->flash('error', 'Anda tidak memiliki akses untuk menghapus soal ini.');
                return;
            }

            // Delete file if exists
            if ($this->soal->path && Storage::disk('public')->exists($this->soal->path)) {
                Storage::disk('public')->delete($this->soal->path);
            }

            // Delete soal
            $this->soal->delete();

            session()->flash('success', 'Soal berhasil dihapus!');
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
        return view('livewire.edit-soal-page');
    }
}
