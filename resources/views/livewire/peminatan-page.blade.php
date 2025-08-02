<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Manage Peminatan</h1>
		</div>

		<!-- Content Row -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">{{ $isEditing ? 'Edit Peminatan' : 'Tambah Peminatan' }}</h6>
								</div>
								<div class="card-body">
										@if (Gate::allows('create-data') || Gate::allows('update-data'))
												<form wire:submit.prevent="save">
														<div class="row">
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="nama" class="form-label">Nama Peminatan</label>
																				<input type="text" class="form-control" id="nama" wire:model="nama">
																				@error('nama')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																{{-- <div class="col-md-4">
																		<div class="form-group">
																				<label for="prodi_id" class="form-label">Program Studi</label>
																				<select class="form-control" id="prodi_id" wire:model="prodi_id">
																						<option value="">Pilih Prodi</option>
																						@foreach ($prodis as $prodi)
																								<option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
																						@endforeach
																				</select>
																				@error('prodi_id')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div> --}}
														</div>
														<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
														@if ($isEditing)
																<button type="button" class="btn btn-sm btn-secondary" wire:click="resetForm">Batal</button>
														@endif
												</form>
										@else
												<p class="text-danger">Anda tidak memiliki izin untuk menambah atau mengedit peminatan.</p>
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
										<h6 class="font-weight-bold text-primary m-0">Daftar Peminatan</h6>
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
														<input type="text" class="form-control" placeholder="Cari peminatan atau prodi..."
																wire:model.live="search">
												</div>
										</div>
										<div class="table-responsive">
												<table class="table-bordered table" id="dataTable" width="100%" cellspacing="0">
														<thead>
																<tr>
																		<th>No</th>
																		<th>Nama Peminatan</th>
																		<th>Program Studi</th>
																		<th>Aksi</th>
																</tr>
														</thead>
														<tbody>
																@forelse($peminatans as $index => $peminatan)
																		<tr>
																				<td>{{ $peminatans->firstItem() + $index }}</td>
																				<td>{{ $peminatan->nama }}</td>
																				<td>{{ $peminatan->prodi ? $peminatan->prodi->nama : '-' }}</td>
																				<td>
																						@if (Gate::allows('update-data'))
																								<button class="btn btn-sm btn-primary" wire:click="edit({{ $peminatan->id }})">Edit</button>
																						@endif
																						@if (Gate::allows('delete-data'))
																								<button class="btn btn-sm btn-danger" wire:click="delete({{ $peminatan->id }})">Hapus</button>
																						@endif
																				</td>
																		</tr>
																@empty
																		<tr>
																				<td colspan="4" class="text-center">Tidak ada data peminatan.</td>
																		</tr>
																@endforelse
														</tbody>
												</table>
												{{ $peminatans->links() }}
										</div>
								</div>
						</div>
				</div>
		</div>


</div>
