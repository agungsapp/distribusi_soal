<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\MataKuliah;
use App\Models\Pengampu;
use App\Models\Soal;
use App\Models\Periode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManageMkPage extends Component
{
    use WithPagination, WithFileUploads;

    public $mk_id;
    public $dosen_id;
    public $pengampu_id;
    public $periode_id;
    public $file;
    public $status = 'draft';
    public $search = '';
    public $selectedStatus = [];

    protected $rules = [
        'periode_id' => 'required|exists:periodes,id',
        'dosen_id' => 'nullable|exists:users,id',
        'file' => 'required|file|mimes:pdf,doc,docx,xlsx|max:10240',
        'status' => 'required|in:draft,publish',
    ];

    public function mount($id)
    {
        $this->mk_id = $id;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->dosen_id = Auth::user()->role === 'dosen' ? Auth::user()->id : null;
        $this->periode_id = null;
        $this->pengampu_id = null;
        $this->file = null;
        $this->status = 'draft';
        $this->selectedStatus = [];
    }

    public function save()
    {
        if (!Gate::allows('create-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengunggah soal.');
            return;
        }

        $this->validate();

        if (Auth::user()->role === 'dosen') {
            $pengampu = Pengampu::where('mata_kuliah_id', $this->mk_id)
                ->where('dosen_id', Auth::user()->id)
                ->where('periode_id', $this->periode_id)
                ->first();

            if (!$pengampu) {
                session()->flash('error', 'Anda tidak terdaftar sebagai pengampu untuk mata kuliah dan periode ini.');
                return;
            }
            $this->pengampu_id = $pengampu->id;
        } else {
            $pengampu = Pengampu::findOrFail($this->pengampu_id);
            if ($pengampu->mata_kuliah_id != $this->mk_id) {
                session()->flash('error', 'Pengampu tidak valid untuk mata kuliah ini.');
                return;
            }
            if ($this->dosen_id && $pengampu->dosen_id != $this->dosen_id) {
                session()->flash('error', 'Dosen tidak sesuai dengan pengampu.');
                return;
            }
        }

        $filePath = $this->file->store('dokumen', 'public');

        Soal::create([
            'pengampu_id' => $this->pengampu_id,
            'dosen_id' => Auth::user()->role === 'dosen' ? Auth::user()->id : $this->dosen_id,
            'path' => $filePath,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Soal berhasil diunggah.');
        $this->resetForm();
        $this->resetPage();
    }

    public function updateStatus($soal_id, $index)
    {
        if (!Gate::allows('update-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengubah status soal.');
            return;
        }

        // Ambil status baru dari $selectedStatus berdasarkan index
        $new_status = $this->selectedStatus[$index] ?? null;

        // Validasi status baru
        $validator = Validator::make(
            ['new_status' => $new_status],
            ['new_status' => 'required|in:draft,publish'],
            [
                'new_status.required' => 'Status harus dipilih.',
                'new_status.in' => 'Status tidak valid.',
            ]
        );

        if ($validator->fails()) {
            session()->flash('error', $validator->errors()->first());
            return;
        }

        $soal = Soal::findOrFail($soal_id);
        if (Auth::user()->role === 'dosen' && $soal->dosen_id !== Auth::user()->id) {
            session()->flash('error', 'Anda hanya dapat mengedit soal yang Anda unggah.');
            return;
        }

        // Simpan perubahan
        $soal->status = $new_status;
        if ($soal->save()) {
            session()->flash('message', 'Status soal berhasil diperbarui.');
        } else {
            session()->flash('error', 'Gagal memperbarui status soal.');
        }

        $this->resetPage();
    }

    public function delete($id)
    {
        if (!Gate::allows('delete-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus soal.');
            return;
        }

        $soal = Soal::findOrFail($id);
        if (Auth::user()->role === 'dosen' && $soal->dosen_id !== Auth::user()->id) {
            session()->flash('error', 'Anda hanya dapat menghapus soal yang Anda unggah.');
            return;
        }

        Storage::disk('public')->delete($soal->path);
        $soal->delete();
        session()->flash('message', 'Soal berhasil dihapus.');
        $this->resetPage();
    }

    public function download($id)
    {
        if (!Gate::allows('read-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengunduh soal.');
            return;
        }
        $soal = Soal::findOrFail($id);
        if (Auth::user()->role === 'dosen' && $soal->dosen_id !== Auth::user()->id) {
            session()->flash('error', 'Anda hanya dapat mengunduh soal yang Anda unggah.');
            return;
        }
        return Storage::disk('public')->download($soal->path);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!Gate::allows('read-data')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk melihat soal.');
            return view('livewire.manage-mk-page', [
                'mataKuliah' => MataKuliah::findOrFail($this->mk_id),
                'periodes' => collect([]),
                'pengampus' => collect([]),
                'soals' => collect([]),
            ]);
        }

        $query = Soal::with(['pengampu.mataKuliah', 'dosen', 'pengampu.periode'])
            ->whereHas('pengampu', fn($q) => $q->where('mata_kuliah_id', $this->mk_id));

        if (Auth::user()->role === 'dosen') {
            $query->where('dosen_id', Auth::user()->id);
        }

        $soals = $query->where(function ($q) {
            $q->where('status', 'like', '%' . $this->search . '%')
                ->orWhereHas('dosen', fn($subQ) => $subQ->where('name', 'like', '%' . $this->search . '%'))
                ->orWhereHas('pengampu.periode', fn($subQ) => $subQ->where('tahun_ajaran', 'like', '%' . $this->search . '%'));
        })->paginate(10);

        // Inisialisasi $selectedStatus dengan status saat ini
        foreach ($soals as $index => $soal) {
            $this->selectedStatus[$index] = $soal->status;
        }

        return view('livewire.manage-mk-page', [
            'mataKuliah' => MataKuliah::findOrFail($this->mk_id),
            'periodes' => Auth::user()->role === 'dosen'
                ? Pengampu::where('mata_kuliah_id', $this->mk_id)
                ->where('dosen_id', Auth::user()->id)
                ->with('periode')
                ->get()
                ->pluck('periode')
                ->filter()
                ->unique('id')
                : Periode::all(),
            'pengampus' => Auth::user()->role === 'admin'
                ? Pengampu::where('mata_kuliah_id', $this->mk_id)->with('dosen')->get()
                : collect([]),
            'soals' => $soals,
        ]);
    }
}
