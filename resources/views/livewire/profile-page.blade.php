<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Profil Pengguna</h1>
		</div>

		<!-- Content Row -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">Informasi Profil</h6>
								</div>
								<div class="card-body">
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
										<form wire:submit.prevent="updateProfile">
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
																		<input type="text" class="form-control" id="role" value="{{ ucfirst($role) }}" readonly>
																</div>
														</div>
														<div class="col-md-4">
																<div class="form-group">
																		<label for="is_active" class="form-label">Status</label>
																		<input type="text" class="form-control" id="is_active"
																				value="{{ $is_active ? 'Aktif' : 'Tidak Aktif' }}" readonly>
																</div>
														</div>
												</div>
												<button type="submit" class="btn btn-sm btn-primary">Simpan Profil</button>
										</form>
								</div>
						</div>
				</div>
		</div>

		<!-- Password Update Form -->
		<div class="row">
				<div class="col-12">
						<div class="card mb-4 shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">Ubah Password</h6>
								</div>
								<div class="card-body">
										<form wire:submit.prevent="updatePassword">
												<div class="row">
														<div class="col-md-4">
																<div class="form-group">
																		<label for="current_password" class="form-label">Password Saat Ini</label>
																		<input type="password" class="form-control" id="current_password" wire:model="current_password">
																		@error('current_password')
																				<span class="text-danger small">{{ $message }}</span>
																		@enderror
																</div>
														</div>
														<div class="col-md-4">
																<div class="form-group">
																		<label for="new_password" class="form-label">Password Baru</label>
																		<input type="password" class="form-control" id="new_password" wire:model="new_password">
																		@error('new_password')
																				<span class="text-danger small">{{ $message }}</span>
																		@enderror
																</div>
														</div>
														<div class="col-md-4">
																<div class="form-group">
																		<label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
																		<input type="password" class="form-control" id="new_password_confirmation"
																				wire:model="new_password_confirmation">
																		@error('new_password_confirmation')
																				<span class="text-danger small">{{ $message }}</span>
																		@enderror
																</div>
														</div>
												</div>
												<button type="submit" class="btn btn-sm btn-primary">Ubah Password</button>
										</form>
								</div>
						</div>
				</div>
		</div>
</div>
