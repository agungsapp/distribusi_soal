<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Manage Mata Kuliah</h1>
		</div>

		<!-- Content Row -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">{{ $isEditing ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' }}</h6>
								</div>
								<div class="card-body">
										@if (Gate::allows('create-data') || Gate::allows('update-data'))
												<form wire:submit.prevent="save">
														<div class="row">
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="nama" class="form-label">Nama Mata Kuliah</label>
																				<input type="text" class="form-control" id="nama" wire:model="nama">
																				@error('nama')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="peminatan_id" class="form-label">Peminatan</label>
																				<select class="form-control" id="peminatan_id" wire:model="peminatan_id">
																						<option value="">Pilih Peminatan</option>
																						@foreach ($peminatans as $peminatan)
																								<option value="{{ $peminatan->id }}">{{ $peminatan->nama }}
																										({{ $peminatan->prodi ? $peminatan->prodi->nama : '-' }})
																								</option>
																						@endforeach
																				</select>
																				@error('peminatan_id')
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
												<p class="text-danger">Anda tidak memiliki izin untuk menambah atau mengedit mata kuliah.</p>
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
										<h6 class="font-weight-bold text-primary m-0">Daftar Mata Kuliah</h6>
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
														<input type="text" class="form-control" placeholder="Cari nama, kode, atau prodi..."
																wire:model.live="search">
												</div>
										</div>
										<div class="table-responsive">
												<table class="table-bordered table" id="dataTable" width="100%" cellspacing="0">
														<thead>
																<tr>
																		<th>No</th>
																		<th>Nama Mata Kuliah</th>
																		<th>Kode</th>
																		<th>Peminatan</th>
																		<th>Prodi</th>
																		<th>Aksi</th>
																</tr>
														</thead>
														<tbody>
																@forelse($mataKuliahs as $index => $mataKuliah)
																		<tr>
																				<td>{{ $mataKuliahs->firstItem() + $index }}</td>
																				<td>{{ $mataKuliah->nama }}</td>
																				<td>{{ $mataKuliah->kode }}</td>
																				<td>{{ $mataKuliah->peminatan ? $mataKuliah->peminatan->nama : '-' }}</td>
																				<td>
																						{{ $mataKuliah->peminatan && $mataKuliah->peminatan->prodi ? $mataKuliah->peminatan->prodi->nama : '-' }}
																				</td>
																				<td>
																						@if (Gate::allows('update-data'))
																								<button class="btn btn-sm btn-primary" wire:click="edit({{ $mataKuliah->id }})">Edit</button>
																						@endif
																						@if (Gate::allows('delete-data'))
																								<button class="btn btn-sm btn-danger" wire:click="delete({{ $mataKuliah->id }})">Hapus</button>
																						@endif
																				</td>
																		</tr>
																@empty
																		<tr>
																				<td colspan="6" class="text-center">Tidak ada data mata kuliah.</td>
																		</tr>
																@endforelse
														</tbody>
												</table>
												{{ $mataKuliahs->links() }}
										</div>
								</div>
						</div>
				</div>
		</div>


</div>
