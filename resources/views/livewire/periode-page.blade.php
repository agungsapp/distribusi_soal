<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Manage Periode</h1>
		</div>

		<!-- Content Row -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">{{ $isEditing ? 'Edit Periode' : 'Tambah Periode' }}</h6>
								</div>
								<div class="card-body">
										@if (Gate::allows('create-data') || Gate::allows('update-data'))
												<form wire:submit.prevent="save">
														<div class="row">
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
																				<input type="text" class="form-control" id="tahun_ajaran" wire:model="tahun_ajaran"
																						placeholder="Contoh: 2024/2025">
																				@error('tahun_ajaran')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="semester" class="form-label">Semester</label>
																				<select class="form-control" id="semester" wire:model="semester">
																						<option value="">Pilih Semester</option>
																						<option value="ganjil">Ganjil</option>
																						<option value="genap">Genap</option>
																				</select>
																				@error('semester')
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
												<p class="text-danger">Anda tidak memiliki izin untuk menambah atau mengedit periode.</p>
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
										<h6 class="font-weight-bold text-primary m-0">Daftar Periode</h6>
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
														<input type="text" class="form-control" placeholder="Cari tahun ajaran atau semester..."
																wire:model.live="search">
												</div>
										</div>
										<div class="table-responsive">
												<table class="table-bordered table" id="dataTable" width="100%" cellspacing="0">
														<thead>
																<tr>
																		<th>No</th>
																		<th>Tahun Ajaran</th>
																		<th>Semester</th>
																		<th>Aksi</th>
																</tr>
														</thead>
														<tbody>
																@forelse($periodes as $index => $periode)
																		<tr>
																				<td>{{ $periodes->firstItem() + $index }}</td>
																				<td>{{ $periode->tahun_ajaran }}</td>
																				<td>{{ ucfirst($periode->semester) }}</td>
																				<td>
																						@if (Gate::allows('update-data'))
																								<button class="btn btn-sm btn-primary" wire:click="edit({{ $periode->id }})">Edit</button>
																						@endif
																						@if (Gate::allows('delete-data'))
																								<button class="btn btn-sm btn-danger" wire:click="delete({{ $periode->id }})">Hapus</button>
																						@endif
																				</td>
																		</tr>
																@empty
																		<tr>
																				<td colspan="4" class="text-center">Tidak ada data periode.</td>
																		</tr>
																@endforelse
														</tbody>
												</table>
												{{ $periodes->links() }}
										</div>
								</div>
						</div>
				</div>
		</div>


</div>
