<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Manage Soal: {{ $mataKuliah->nama }} ({{ $mataKuliah->kode }})</h1>
		</div>

		<!-- Form Upload -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">Unggah Soal</h6>
								</div>
								<div class="card-body">
										@if (Gate::allows('create-data'))
												<form wire:submit.prevent="save">
														<div class="row">
																@if (auth()->user()->role === 'dosen')
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
																@else
																		<div class="col-md-4">
																				<div class="form-group">
																						<label for="pengampu_id" class="form-label">Pengampu</label>
																						<select class="form-control" id="pengampu_id" wire:model="pengampu_id">
																								<option value="">Pilih Pengampu</option>
																								@foreach ($pengampus as $pengampu)
																										<option value="{{ $pengampu->id }}">{{ $pengampu->dosen ? $pengampu->dosen->name : '-' }}
																												({{ $pengampu->periode ? $pengampu->periode->tahun_ajaran . ' - ' . ucfirst($pengampu->periode->semester) : '-' }})
																										</option>
																								@endforeach
																						</select>
																						@error('pengampu_id')
																								<span class="text-danger small">{{ $message }}</span>
																						@enderror
																				</div>
																		</div>
																		<div class="col-md-4">
																				<div class="form-group">
																						<label for="dosen_id" class="form-label">Dosen</label>
																						<select class="form-control" id="dosen_id" wire:model="dosen_id">
																								<option value="">Pilih Dosen</option>
																								@foreach ($pengampus as $pengampu)
																										@if ($pengampu->dosen)
																												<option value="{{ $pengampu->dosen->id }}">{{ $pengampu->dosen->name }}</option>
																										@endif
																								@endforeach
																						</select>
																						@error('dosen_id')
																								<span class="text-danger small">{{ $message }}</span>
																						@enderror
																				</div>
																		</div>
																@endif
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="file" class="form-label">File Soal</label>
																				<input type="file" class="form-control" id="file" wire:model="file">
																				@error('file')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="status" class="form-label">Status</label>
																				<select class="form-control" id="status" wire:model="status">
																						<option value="draft">Draft</option>
																						<option value="publish">Publish</option>
																				</select>
																				@error('status')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
														</div>
														<button type="submit" class="btn btn-sm btn-primary">Unggah</button>
														<button type="button" class="btn btn-sm btn-secondary" wire:click="resetForm">Reset</button>
												</form>
										@else
												<p class="text-danger">Anda tidak memiliki izin untuk mengunggah soal.</p>
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
										<h6 class="font-weight-bold text-primary m-0">Daftar Soal</h6>
								</div>
								<div class="card-body">
										<div class="row mb-3">
												<div class="col-md-4">
														<input type="text" class="form-control" placeholder="Cari status, dosen, atau periode..."
																wire:model.live="search">
												</div>
										</div>
										<div class="table-responsive">
												<table class="table-bordered table" id="dataTable" width="100%" cellspacing="0">
														<thead>
																<tr>
																		<th>No</th>
																		<th>Nama File</th>
																		<th>Dosen</th>
																		<th>Periode</th>
																		<th>Status</th>
																		<th>Tanggal Unggah</th>
																		<th>Aksi</th>
																</tr>
														</thead>
														<tbody>
																@forelse($soals as $index => $soal)
																		<tr>
																				<td>{{ $soals->firstItem() + $index }}</td>
																				<td>{{ basename($soal->path) }}</td>
																				<td>{{ $soal->dosen ? $soal->dosen->name : '-' }}</td>
																				<td>
																						{{ $soal->pengampu && $soal->pengampu->periode ? $soal->pengampu->periode->tahun_ajaran . ' - ' . ucfirst($soal->pengampu->periode->semester) : '-' }}
																				</td>
																				<td>
																						@if (Gate::allows('update-data'))
																								<select class="form-control form-control-sm"
																										wire:model.live="selectedStatus.{{ $index }}"
																										wire:change="updateStatus({{ $soal->id }}, {{ $index }})">
																										<option value="draft" {{ $soal->status === 'draft' ? 'selected' : '' }}>Draft</option>
																										<option value="publish" {{ $soal->status === 'publish' ? 'selected' : '' }}>Publish</option>
																								</select>
																						@else
																								{{ ucfirst($soal->status) }}
																						@endif
																				</td>
																				<td>{{ $soal->created_at->format('d/m/Y H:i') }}</td>
																				<td>
																						<button class="btn btn-sm btn-success"
																								wire:click="download({{ $soal->id }})">Download</button>
																						@if (Gate::allows('delete-data'))
																								<button class="btn btn-sm btn-danger" wire:click="delete({{ $soal->id }})">Hapus</button>
																						@endif
																				</td>
																		</tr>
																@empty
																		<tr>
																				<td colspan="7" class="text-center">Tidak ada data soal.</td>
																		</tr>
																@endforelse
														</tbody>
												</table>
												{{ $soals->links() }}
										</div>
								</div>
						</div>
				</div>
		</div>

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
