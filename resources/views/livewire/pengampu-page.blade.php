<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Manage Pengampu</h1>
		</div>

		<!-- Content Row -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">{{ $isEditing ? 'Edit Pengampu' : 'Tambah Pengampu' }}</h6>
								</div>
								<div class="card-body">
										@if (Gate::allows('create-data') || Gate::allows('update-data'))
												<form wire:submit.prevent="save">
														<div class="row">
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="mata_kuliah_id" class="form-label">Mata Kuliah</label>
																				<select class="form-control" id="mata_kuliah_id" wire:model="mata_kuliah_id">
																						<option value="">Pilih Mata Kuliah</option>
																						@foreach ($mataKuliahs as $mataKuliah)
																								<option value="{{ $mataKuliah->id }}">{{ $mataKuliah->nama }}
																										({{ $mataKuliah->peminatan && $mataKuliah->peminatan->prodi ? $mataKuliah->peminatan->prodi->nama : '-' }})
																								</option>
																						@endforeach
																				</select>
																				@error('mata_kuliah_id')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="dosen_id" class="form-label">Dosen</label>
																				<select class="form-control" id="dosen_id" wire:model="dosen_id">
																						<option value="">Pilih Dosen</option>
																						@foreach ($dosens as $dosen)
																								<option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
																						@endforeach
																				</select>
																				@error('dosen_id')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="periode_id" class="form-label">Periode</label>
																				<select class="form-control" id="periode_id" wire:model="periode_id">
																						<option value="">Pilih Periode</option>
																						@foreach ($periodes as $periode)
																								<option value="{{ $periode->id }}">{{ $periode->tahun_ajaran }} -
																										{{ ucfirst($periode->semester) }}</option>
																						@endforeach
																				</select>
																				@error('periode_id')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
														</div>
														<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
														@if ($isEditing)
																<button type="button" class="btn btn-sm btn-secondary" wire:click="resetForm">Batal</button>
														@endif
												</form>
										@else
												<p class="text-danger">Anda tidak memiliki izin untuk menambah atau mengedit pengampu.</p>
										@endif
								</div>
						</div>
				</div>
		</div>

		<!-- Search and Data Table -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">Daftar Pengampu</h6>
								</div>
								<div class="card-body">
										<div class="row mb-3">
												<div class="col-12">
														@if (session()->has('message'))
																<div class="alert alert-success alert-dismissible fade show" role="alert">
																		{{ session('message') }}
																		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																		</button>
																</div>
														@endif
														@if (session()->has('error'))
																<div class="alert alert-danger alert-dismissible fade show" role="alert">
																		{{ session('error') }}
																		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																		</button>
																</div>
														@endif
												</div>
										</div>
										<div class="row mb-3">
												<div class="col-md-4">
														<input type="text" class="form-control" placeholder="Cari mata kuliah, dosen, atau periode..."
																wire:model.live="search">
												</div>
										</div>
										<div class="table-responsive">
												<table class="table-bordered table" id="dataTable" width="100%" cellspacing="0">
														<thead>
																<tr>
																		<th>No</th>
																		<th>Mata Kuliah</th>
																		<th>Prodi</th>
																		<th>Dosen</th>
																		<th>Periode</th>
																		<th>Aksi</th>
																</tr>
														</thead>
														<tbody>
																@forelse($pengampus as $index => $pengampu)
																		<tr>
																				<td>{{ $pengampus->firstItem() + $index }}</td>
																				<td>{{ $pengampu->mataKuliah ? $pengampu->mataKuliah->nama : '-' }}</td>
																				<td>
																						{{ $pengampu->mataKuliah && $pengampu->mataKuliah->peminatan && $pengampu->mataKuliah->peminatan->prodi ? $pengampu->mataKuliah->peminatan->prodi->nama : '-' }}
																				</td>
																				<td>{{ $pengampu->dosen ? $pengampu->dosen->name : '-' }}</td>
																				<td>
																						{{ $pengampu->periode ? $pengampu->periode->tahun_ajaran . ' - ' . ucfirst($pengampu->periode->semester) : '-' }}
																				</td>
																				<td>
																						@if (Gate::allows('update-data'))
																								<button class="btn btn-sm btn-primary" wire:click="edit({{ $pengampu->id }})">Edit</button>
																						@endif
																						@if (Gate::allows('delete-data'))
																								<button class="btn btn-sm btn-danger" wire:click="delete({{ $pengampu->id }})">Hapus</button>
																						@endif
																				</td>
																		</tr>
																@empty
																		<tr>
																				<td colspan="6" class="text-center">Tidak ada data pengampu.</td>
																		</tr>
																@endforelse
														</tbody>
												</table>
												{{ $pengampus->links() }}
										</div>
								</div>
						</div>
				</div>
		</div>


</div>
