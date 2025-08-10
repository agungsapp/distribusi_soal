<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Tambah Soal Baru</h1>
				<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
								<li class="breadcrumb-item"><a href="{{ route('all-mata-kuliah') }}">Semua Mata Kuliah</a></li>
								<li class="breadcrumb-item active">Tambah Soal</li>
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

		<!-- Main Form -->
		<div class="row">
				<div class="col-lg-8">
						<div class="card shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">
												<i class="fas fa-plus-circle mr-2"></i>Informasi Soal
										</h6>
								</div>
								<div class="card-body">
										<form wire:submit.prevent="save">
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
																						<strong>{{ $user->name }}</strong>
																						<span class="badge badge-info ml-2">Anda</span>
																				</div>
																		</div>
																</div>
														@endif
												</div>


												<div>
														<div>
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

												<!-- File Upload -->
												<div class="mb-3">
														<label for="file" class="form-label">
																<i class="fas fa-file-upload mr-1"></i>File Soal <span class="text-danger">*</span>
														</label>
														<input type="file" wire:model="file" id="file"
																class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx">
														<small class="form-text text-muted">
																Format yang didukung: PDF, DOC, DOCX. Maksimal 10MB.
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
														<div>
																<button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
																		<span wire:loading.remove wire:target="save">
																				<i class="fas fa-save mr-2"></i>Simpan Soal
																		</span>
																		<span wire:loading wire:target="save">
																				<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...
																		</span>
																</button>
														</div>
												</div>
										</form>
								</div>
						</div>
				</div>

				<!-- Info Panel -->
				<div class="col-lg-4">
						<!-- Pengampu Info -->
						@if ($mata_kuliah_id && $periode_id && ($isAdmin ? $dosen_id : true))
								<div class="card mb-4 shadow">
										<div class="card-header py-3">
												<h6 class="font-weight-bold text-info m-0">
														<i class="fas fa-info-circle mr-2"></i>Informasi Pengampu
												</h6>
										</div>
										<div class="card-body">
												@if ($pengampus->isNotEmpty())
														<div class="alert alert-success">
																<i class="fas fa-check-circle mr-2"></i>
																Pengampu sudah tersedia untuk kombinasi ini.
														</div>
												@else
														<div class="alert alert-warning">
																<i class="fas fa-exclamation-triangle mr-2"></i>
																Pengampu belum ada. Sistem akan otomatis membuat pengampu baru.
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
														Anda hanya dapat menambah soal untuk mata kuliah yang Anda ampu.
												</small>
										@endif
								</div>
						</div>

						<!-- Help Card -->
						<div class="card shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-success m-0">
												<i class="fas fa-question-circle mr-2"></i>Bantuan
										</h6>
								</div>
								<div class="card-body">
										<ul class="list-unstyled mb-0">
												<li class="mb-2">
														<i class="fas fa-check text-success mr-2"></i>
														Pilih mata kuliah, dosen, dan periode
												</li>
												<li class="mb-2">
														<i class="fas fa-check text-success mr-2"></i>
														Upload file soal (PDF/DOC/DOCX)
												</li>
												<li class="mb-2">
														<i class="fas fa-check text-success mr-2"></i>
														Tentukan status publikasi
												</li>
												<li>
														<i class="fas fa-info text-info mr-2"></i>
														Pengampu akan dibuat otomatis jika belum ada
												</li>
										</ul>
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
		</style>
@endpush
