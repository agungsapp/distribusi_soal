<div>
		<div class="container-fluid">
				<!-- Page Heading -->
				<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800">Edit Soal</h1>
						<nav aria-label="breadcrumb">
								<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
										<li class="breadcrumb-item"><a href="{{ route('all-mata-kuliah') }}">Semua Mata Kuliah</a></li>
										<li class="breadcrumb-item active">Edit Soal</li>
								</ol>
						</nav>
				</div>

				<!-- Flash Messages -->
				@if (session()->has('success'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">
								{{ session('success') }}
								<button type="button" class="close" data-dismiss="alert">
										<span>&times;</span>
								</button>
						</div>
				@endif

				@if (session()->has('error'))
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
								{{ session('error') }}
								<button type="button" class="close" data-dismiss="alert">
										<span>&times;</span>
								</button>
						</div>
				@endif

				<!-- Main Content -->
				<div class="row">
						<div class="col-lg-8">
								<form wire:submit.prevent="update">
										<div class="card shadow">
												<div class="card-header py-3">
														<h6 class="font-weight-bold text-primary m-0">
																<i class="fas fa-edit mr-2"></i>Edit Informasi Soal
														</h6>
												</div>
												<div class="card-body">
														<div class="row">
																<!-- Mata Kuliah -->
																<div class="col-md-6 mb-3">
																		<label for="mata_kuliah_id" class="form-label">
																				<i class="fas fa-book mr-1"></i>Mata Kuliah <span class="text-danger">*</span>
																		</label>
																		<select wire:model.live="mata_kuliah_id" id="mata_kuliah_id"
																				class="form-control @error('mata_kuliah_id') is-invalid @enderror">
																				<option value="">-- Pilih Mata Kuliah --</option>
																				@foreach ($mata_kuliahs as $mk)
																						<option value="{{ $mk->id }}">
																								{{ $mk->nama }} ({{ $mk->kode }})
																								@if ($mk->peminatan)
																										- {{ $mk->peminatan->nama }}
																								@endif
																						</option>
																				@endforeach
																		</select>
																		@error('mata_kuliah_id')
																				<div class="invalid-feedback">{{ $message }}</div>
																		@enderror
																</div>

																<!-- Dosen (Only for Admin) -->
																@if ($isAdmin)
																		<div class="col-md-6 mb-3">
																				<label for="dosen_id" class="form-label">
																						<i class="fas fa-user-tie mr-1"></i>Dosen <span class="text-danger">*</span>
																				</label>
																				<select wire:model.live="dosen_id" id="dosen_id"
																						class="form-control @error('dosen_id') is-invalid @enderror">
																						<option value="">-- Pilih Dosen --</option>
																						@foreach ($dosens as $dosen)
																								<option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
																						@endforeach
																				</select>
																				@error('dosen_id')
																						<div class="invalid-feedback">{{ $message }}</div>
																				@enderror
																		</div>
																@else
																		<!-- Show current dosen for non-admin -->
																		<div class="col-md-6 mb-3">
																				<label class="form-label">
																						<i class="fas fa-user-tie mr-1"></i>Dosen
																				</label>
																				<div class="form-control-plaintext bg-light rounded p-3">
																						<div class="d-flex align-items-center">
																								<div
																										class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2">
																										<i class="fas fa-user text-white"></i>
																								</div>
																								<strong>{{ $soal->pengampu->dosen->name }}</strong>
																								@if ($soal->pengampu->dosen->id === $user->id)
																										<span class="badge badge-info ml-2">Anda</span>
																								@endif
																						</div>
																				</div>
																		</div>
																@endif
														</div>

														<div class="row">
																<!-- Periode -->
																<div class="col-md-6 mb-3">
																		<label for="periode_id" class="form-label">
																				<i class="fas fa-calendar-alt mr-1"></i>Periode <span class="text-danger">*</span>
																		</label>
																		<select wire:model.live="periode_id" id="periode_id"
																				class="form-control @error('periode_id') is-invalid @enderror">
																				<option value="">-- Pilih Periode --</option>
																				@foreach ($periodes as $periode)
																						<option value="{{ $periode->id }}">
																								{{ $periode->tahun_ajaran }} - {{ ucfirst($periode->semester) }}
																						</option>
																				@endforeach
																		</select>
																		@error('periode_id')
																				<div class="invalid-feedback">{{ $message }}</div>
																		@enderror
																</div>

																<!-- Status -->
																<div class="col-md-6 mb-3">
																		<label for="status" class="form-label">
																				<i class="fas fa-toggle-on mr-1"></i>Status <span class="text-danger">*</span>
																		</label>
																		<select wire:model="status" id="status" class="form-control @error('status') is-invalid @enderror">
																				<option value="draft">Draft</option>
																				<option value="publish">Publish</option>
																		</select>
																		@error('status')
																				<div class="invalid-feedback">{{ $message }}</div>
																		@enderror
																</div>
														</div>

														<!-- Current File Info -->
														@if ($current_file)
																<div class="mb-3">
																		<label class="form-label">
																				<i class="fas fa-file mr-1"></i>File Saat Ini
																		</label>
																		<div class="alert alert-info">
																				<div class="d-flex justify-content-between align-items-center">
																						<div>
																								<i class="fas fa-file-pdf text-danger mr-2"></i>
																								<strong>{{ basename($current_file) }}</strong>
																						</div>
																						<a href="{{ asset('storage/' . $current_file) }}" class="btn btn-sm btn-outline-primary"
																								target="_blank">
																								<i class="fas fa-eye mr-1"></i>Lihat
																						</a>
																				</div>
																		</div>
																</div>
														@endif

														<!-- File Upload -->
														<div class="mb-3">
																<label for="file" class="form-label">
																		<i class="fas fa-file-upload mr-1"></i>
																		@if ($current_file)
																				Ganti File Soal (Opsional)
																		@else
																				File Soal <span class="text-danger">*</span>
																		@endif
																</label>
																<input type="file" wire:model="file" id="file"
																		class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx">
																<small class="form-text text-muted">
																		Format yang didukung: PDF, DOC, DOCX. Maksimal 10MB.
																		@if ($current_file)
																				Biarkan kosong jika tidak ingin mengganti file.
																		@endif
																</small>
																@error('file')
																		<div class="invalid-feedback">{{ $message }}</div>
																@enderror

																<!-- Loading indicator for file upload -->
																<div wire:loading wire:target="file" class="mt-2">
																		<div class="progress">
																				<div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%">
																						Uploading...
																				</div>
																		</div>
																</div>
														</div>

														<!-- Action Buttons -->
														<div class="d-flex justify-content-between align-items-center mt-4">
																<button type="button" wire:click="cancel" class="btn btn-secondary">
																		<i class="fas fa-arrow-left mr-2"></i>Kembali
																</button>
																<div class="btn-group">
																		<button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
																				<span wire:loading.remove wire:target="update">
																						<i class="fas fa-save mr-2"></i>Update Soal
																				</span>
																				<span wire:loading wire:target="update">
																						<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...
																				</span>
																		</button>
																		<button type="button" class="btn btn-danger" onclick="confirmDelete()"
																				wire:loading.attr="disabled">
																				<i class="fas fa-trash mr-2"></i>Hapus
																		</button>
																</div>
														</div>
												</div>
										</div>
								</form>
						</div>

						<!-- Info Panel -->
						<div class="col-lg-4">
								<!-- Current Soal Info -->
								<div class="card mb-4 shadow">
										<div class="card-header py-3">
												<h6 class="font-weight-bold text-info m-0">
														<i class="fas fa-info-circle mr-2"></i>Informasi Soal
												</h6>
										</div>
										<div class="card-body">
												<table class="table-borderless table-sm table">
														<tr>
																<td><strong>ID Soal:</strong></td>
																<td>{{ $soal->id }}</td>
														</tr>
														<tr>
																<td><strong>Dibuat:</strong></td>
																<td>{{ $soal->created_at->format('d M Y H:i') }}</td>
														</tr>
														<tr>
																<td><strong>Diupdate:</strong></td>
																<td>{{ $soal->updated_at->format('d M Y H:i') }}</td>
														</tr>
														<tr>
																<td><strong>Status:</strong></td>
																<td>
																		<span class="badge badge-{{ $soal->status == 'publish' ? 'success' : 'warning' }}">
																				{{ ucfirst($soal->status) }}
																		</span>
																</td>
														</tr>
												</table>
										</div>
								</div>

								<!-- Pengampu Info -->
								@if ($mata_kuliah_id && $periode_id && ($isAdmin ? $dosen_id : true))
										<div class="card mb-4 shadow">
												<div class="card-header py-3">
														<h6 class="font-weight-bold text-success m-0">
																<i class="fas fa-users mr-2"></i>Informasi Pengampu
														</h6>
												</div>
												<div class="card-body">
														@if (!empty($pengampus))
																<div class="alert alert-success">
																		<i class="fas fa-check-circle mr-2"></i>
																		Pengampu sudah tersedia.
																</div>
														@else
																<div class="alert alert-warning">
																		<i class="fas fa-exclamation-triangle mr-2"></i>
																		Pengampu baru akan dibuat jika kombinasi berbeda.
																</div>
														@endif
												</div>
										</div>
								@endif

								<!-- User Role Info -->
								<div class="card mb-4 shadow">
										<div class="card-header py-3">
												<h6 class="font-weight-bold text-{{ $isAdmin ? 'danger' : 'success' }} m-0">
														<i class="fas fa-user-shield mr-2"></i>Informasi User
												</h6>
										</div>
										<div class="card-body">
												<div class="d-flex align-items-center justify-content-between">
														<div>
																<strong>{{ $user->name }}</strong>
																<br><small class="text-muted">{{ $user->email }}</small>
														</div>
														<span class="badge badge-{{ $isAdmin ? 'danger' : 'success' }} badge-lg">
																{{ strtoupper($user->role) }}
														</span>
												</div>
												@if (!$isAdmin)
														<hr>
														<small class="text-muted">
																<i class="fas fa-info-circle mr-1"></i>
																Anda hanya dapat mengedit soal milik Anda sendiri.
														</small>
												@endif
										</div>
								</div>

								<!-- Help Card -->
								<div class="card shadow">
										<div class="card-header py-3">
												<h6 class="font-weight-bold text-warning m-0">
														<i class="fas fa-exclamation-triangle mr-2"></i>Peringatan
												</h6>
										</div>
										<div class="card-body">
												<ul class="list-unstyled mb-0 text-sm">
														<li class="mb-2">
																<i class="fas fa-info text-info mr-2"></i>
																File lama akan dihapus jika Anda upload file baru
														</li>
														<li class="mb-2">
																<i class="fas fa-warning text-warning mr-2"></i>
																Aksi hapus tidak dapat dibatalkan
														</li>
														<li>
																<i class="fas fa-shield-alt text-success mr-2"></i>
																Pastikan backup file penting sebelum mengedit
														</li>
												</ul>
										</div>
								</div>
						</div>
				</div>
		</div>

		<!-- Delete Confirmation Modal -->
		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
						<div class="modal-content">
								<div class="modal-header">
										<h5 class="modal-title">
												<i class="fas fa-exclamation-triangle text-danger mr-2"></i>
												Konfirmasi Hapus
										</h5>
										<button type="button" class="close" data-dismiss="modal">
												<span>&times;</span>
										</button>
								</div>
								<div class="modal-body">
										<p>Apakah Anda yakin ingin menghapus soal ini?</p>
										<div class="alert alert-danger">
												<i class="fas fa-exclamation-triangle mr-2"></i>
												<strong>Peringatan:</strong> Aksi ini tidak dapat dibatalkan dan file akan dihapus permanen.
										</div>
								</div>
								<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">
												<i class="fas fa-times mr-2"></i>Batal
										</button>
										<button type="button" class="btn btn-danger" wire:click="delete" data-dismiss="modal">
												<i class="fas fa-trash mr-2"></i>Ya, Hapus
										</button>
								</div>
						</div>
				</div>
		</div>
</div>

@push('css')
		<style>
				.breadcrumb {
						background-color: transparent;
						margin-bottom: 0;
				}

				.form-label {
						font-weight: 600;
						margin-bottom: 0.5rem;
				}

				.progress {
						height: 20px;
				}

				.card {
						transition: transform 0.2s;
				}

				.card:hover {
						transform: translateY(-2px);
				}

				.btn {
						border-radius: 0.375rem;
				}

				.table-borderless td {
						border: none;
						padding: 0.25rem 0.5rem;
				}

				.text-sm {
						font-size: 0.875rem;
				}
		</style>
@endpush

@push('js')
		<script>
				function confirmDelete() {
						$('#deleteModal').modal('show');
				}
		</script>
@endpush
