<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Semua Mata Kuliah</h1>
        <a href="{{ route('soal.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Tambah Soal Baru
        </a>
    </div>

    <!-- Filter Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                                    <input type="text" class="form-control" id="nama_mk" wire:model.live="nama_mk" placeholder="Cari nama mata kuliah...">
                                    @error('nama_mk')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                                    <input type="text" class="form-control" id="kode_mk" wire:model.live="kode_mk" placeholder="Cari kode mata kuliah...">
                                    @error('kode_mk')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="periode" class="form-label">Periode</label>
                                    <input type="text" class="form-control" id="periode" wire:model.live="periode" placeholder="Cari periode...">
                                    @error('periode')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dosen" class="form-label">Dosen</label>
                                    <input type="text" class="form-control" id="dosen" wire:model.live="dosen" placeholder="Cari nama dosen...">
                                    @error('dosen')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="peminatan" class="form-label">Peminatan</label>
                                    <input type="text" class="form-control" id="peminatan" wire:model.live="peminatan" placeholder="Cari peminatan...">
                                    @error('peminatan')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-group w-100">
                                    <button type="button" class="btn btn-secondary w-100" wire:click="$refresh">
                                        <i class="fas fa-sync-alt"></i> Reset Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="row my-3">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari nama, kode, dosen, atau soal..." wire:model.live="search">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <small class="text-muted">
                @if($soals->total() > 0)
                    Menampilkan {{ $soals->firstItem() }} - {{ $soals->lastItem() }} dari {{ $soals->total() }} soal
                @endif
            </small>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%;">No.</th>
                                    <th style="width: 25%;">Mata Kuliah</th>
                                    <th style="width: 15%;">Periode</th>
                                    <th style="width: 20%;">Dosen</th>
                                    <th style="width: 25%;">Soal</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($soals as $soal)
                                    <tr>
                                        <td>{{ $loop->iteration + ($soals->firstItem() - 1) }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $soal->pengampu->mataKuliah->nama ?? 'N/A' }}</strong>
                                                @if($soal->pengampu->mataKuliah->kode)
                                                    <br><small class="text-muted">{{ $soal->pengampu->mataKuliah->kode }}</small>
                                                @endif
                                                @if($soal->pengampu->mataKuliah->peminatan)
                                                    <br><small class="badge badge-info">{{ $soal->pengampu->mataKuliah->peminatan->nama }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($soal->pengampu->periode)
                                                {{ explode('/', $soal->pengampu->periode->tahun_ajaran)[0] }}
                                                <br><small class="text-muted">{{ $soal->pengampu->periode->nama ?? '' }}</small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($soal->pengampu->dosen)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    <span>{{ $soal->pengampu->dosen->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Tidak ada dosen</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($soal->path)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                    <span class="text-truncate" style="max-width: 200px;" title="{{ basename($soal->path) }}">
                                                        {{ basename($soal->path) }}
                                                    </span>
                                                </div>
                                                @if($soal->created_at)
                                                    <small class="text-muted d-block">
                                                        Upload: {{ $soal->created_at->format('d M Y') }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php 
                                                $user = Auth::user();
                                                $canEdit = $user->role === 'admin' || $soal->dosen_id === $user->id;
                                            @endphp
                                            
                                            @if($soal->path && file_exists(storage_path('app/public/' . $soal->path)))
                                                <div class="btn-group" role="group">
                                                    <a href="{{ asset('storage/' . $soal->path) }}" 
                                                       class="btn btn-sm btn-success" 
                                                       target="_blank"
                                                       title="Download {{ basename($soal->path) }}">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    @if($canEdit)
                                                        <a href="{{ route('soal.edit', $soal->id) }}" 
                                                           class="btn btn-sm btn-warning"
                                                           title="Edit soal">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-secondary" disabled title="File tidak tersedia">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                    @if($canEdit)
                                                        <a href="{{ route('soal.edit', $soal->id) }}" 
                                                           class="btn btn-sm btn-warning"
                                                           title="Edit soal">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Tidak ada data yang ditemukan</h5>
                                                <p class="text-muted">Coba ubah kata kunci pencarian atau filter yang digunakan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($soals->hasPages())
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $soals->links() }}
            </div>
        </div>
    @endif
</div>

@push('css')
		<style>
.avatar-sm {
    width: 30px;
    height: 30px;
    font-size: 12px;
}

.table th {
    border-top: none;
    font-weight: 600;
    background-color: #f8f9fa;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.075);
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush