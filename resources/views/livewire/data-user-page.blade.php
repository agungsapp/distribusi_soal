<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Manage User</h1>
		</div>

		<!-- Content Row -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">{{ $isEditing ? 'Edit User' : 'Tambah User' }}</h6>
								</div>
								<div class="card-body">
										@if (Gate::allows('create-data') || Gate::allows('update-data'))
												<form wire:submit.prevent="save">
														<div class="row">
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="name" class="form-label">Nama</label>
																				<input type="text" class="form-control" id="name" wire:model="name">
																				@error('name')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="email" class="form-label">Email</label>
																				<input type="email" class="form-control" id="email" wire:model="email">
																				@error('email')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="nidn" class="form-label">NIDN</label>
																				<input type="text" class="form-control" id="nidn" wire:model="nidn">
																				@error('nidn')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="role" class="form-label">Role</label>
																				<select class="form-control" id="role" wire:model="role">
																						<option value="dosen">Dosen</option>
																						<option value="admin">Admin</option>
																				</select>
																				@error('role')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																</div>
																<div class="col-md-4">
																		<div class="form-group">
																				<label for="is_active" class="form-label">Status</label>
																				<select class="form-control" id="is_active" wire:model="is_active">
																						<option value="1">Aktif</option>
																						<option value="0">Tidak Aktif</option>
																				</select>
																				@error('is_active')
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
												<p class="text-danger">Anda tidak memiliki izin untuk menambah atau mengedit user.</p>
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
										<h6 class="font-weight-bold text-primary m-0">Daftar User</h6>
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
														<input type="text" class="form-control" placeholder="Cari nama, email, atau NIDN..."
																wire:model.live="search">
												</div>
										</div>
										<div class="table-responsive">
												<table class="table-bordered table" id="dataTable" width="100%" cellspacing="0">
														<thead>
																<tr>
																		<th>No</th>
																		<th>Nama</th>
																		<th>Email</th>
																		<th>NIDN</th>
																		<th>Role</th>
																		<th>Status</th>
																		<th>Aksi</th>
																</tr>
														</thead>
														<tbody>
																@forelse($users as $index => $user)
																		<tr>
																				<td>{{ $users->firstItem() + $index }}</td>
																				<td>{{ $user->name }}</td>
																				<td>{{ $user->email }}</td>
																				<td>{{ $user->nidn ?? '-' }}</td>
																				<td>{{ ucfirst($user->role) }}</td>
																				<td>
																						@if (Gate::allows('update-data'))
																								<select class="form-control"
																										wire:change="updateStatus({{ $user->id }}, $event.target.value)">
																										<option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
																										<option value="0" {{ !$user->is_active ? 'selected' : '' }}>Tidak Aktif</option>
																								</select>
																						@else
																								{{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
																						@endif
																				</td>
																				<td>
																						@if (Gate::allows('update-data'))
																								<button class="btn btn-sm btn-primary" wire:click="edit({{ $user->id }})">Edit</button>
																								<button class="btn btn-sm btn-warning" wire:click="resetPassword({{ $user->id }})">Reset
																										Password</button>
																						@endif
																						@if (Gate::allows('delete-data'))
																								<button class="btn btn-sm btn-danger" wire:click="delete({{ $user->id }})">Hapus</button>
																						@endif
																				</td>
																		</tr>
																@empty
																		<tr>
																				<td colspan="7" class="text-center">Tidak ada data user.</td>
																		</tr>
																@endforelse
														</tbody>
												</table>
												{{ $users->links() }}
										</div>
								</div>
						</div>
				</div>
		</div>
</div>
