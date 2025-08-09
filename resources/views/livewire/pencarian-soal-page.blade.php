<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Pencarian Soal</h1>
				<a href="{{ route('soal.create') }}" class="btn btn-primary">
						<i class="fas fa-plus mr-2"></i>Tambah Soal Baru
				</a>
		</div>

		<!-- Filter Form -->
		<div class="row">
				<div class="col-12">
						<div class="card shadow">
								<div class="card-header py-3">
										<h6 class="font-weight-bold text-primary m-0">
												<i class="fas fa-filter mr-2"></i>Filter Pencarian
										</h6>
								</div>
								<div class="card-body">
										<form>
												<div class="row">
														<!-- Global Search -->
														<div class="col-md-6 mb-3">
																<label for="search" class="form-label">
																		<i class="fas fa-search mr-1"></i>Pencarian Global
																</label>
																<input type="text" class="form-control" id="search" wire:model.live="search"
																		placeholder="Cari nama, kode, dosen, file soal...">
																@error('search')
																		<span class="text-danger small">{{ $message }}</span>
																@enderror
														</div>

														<!-- Mata Kuliah -->
														<div class="col-md-6 mb-3">
																<label for="mata_kuliah_id" class="form-label">
																		<i class="fas fa-book mr-1"></i>Mata Kuliah
																</label>
																<select class="form-control" id="mata_kuliah_id" wire:model.live="mata_kuliah_id">
																		<option value="">-- Pilih Mata Kuliah --</option>
																		@foreach ($mataKuliahs as $mk)
																				<option value="{{ $mk->id }}">
																						{{ $mk->nama }} ({{ $mk->kode }})
																				</option>
																		@endforeach
																</select>
																@error('mata_kuliah_id')
																		<span class="text-danger small">{{ $message }}</span>
																@enderror
														</div>

														<!-- Periode -->
														<div class="col-md-6 mb-3">
																<label for="periode_id" class="form-label">
																		<i class="fas fa-calendar-alt mr-1"></i>Periode
																</label>
																<select class="form-control" id="periode_id" wire:model.live="periode_id">
																		<option value="">-- Pilih Periode --</option>
																		@foreach ($periodes as $periode)
																				<option value="{{ $periode->id }}">
																						{{ $periode->tahun_ajaran }} - {{ ucfirst($periode->semester) }}
																				</option>
																		@endforeach
																</select>
																@error('periode_id')
																		<span class="text-danger small">{{ $message }}</span>
																@enderror
														</div>

														<!-- Dosen -->
														<div class="col-md-6 mb-3">
																<label for="dosen_id" class="form-label">
																		<i class="fas fa-user-tie mr-1"></i>Dosen
																</label>
																<select class="form-control" id="dosen_id" wire:model.live="dosen_id">
																		<option value="">-- Pilih Dosen --</option>
																		@foreach ($dosens as $dosen)
																				<option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
																		@endforeach
																</select>
																@error('dosen_id')
																		<span class="text-danger small">{{ $message }}</span>
																@enderror
														</div>

														<!-- Peminatan -->
														<div class="col-md-6 mb-3">
																<label for="peminatan_id" class="form-label">
																		<i class="fas fa-graduation-cap mr-1"></i>Peminatan
																</label>
																<select class="form-control" id="peminatan_id" wire:model.live="peminatan_id">
																		<option value="">-- Pilih Peminatan --</option>
																		@foreach ($peminatans as $peminatan)
																				<option value="{{ $peminatan->id }}">{{ $peminatan->nama }}</option>
																		@endforeach
																</select>
																@error('peminatan_id')
																		<span class="text-danger small">{{ $message }}</span>
																@enderror
														</div>

														<!-- Reset Button -->
														<div class="col-md-6 d-flex align-items-end mb-3">
																<button type="button" class="btn btn-secondary w-100" wire:click="resetFilters">
																		<i class="fas fa-sync-alt mr-2"></i>Reset Filter
																</button>
														</div>
												</div>

												<!-- Filter Status Info -->
												@if ($hasFilters)
														<div class="alert alert-info">
																<i class="fas fa-info-circle mr-2"></i>
																<strong>Filter aktif:</strong>
																@if (!empty($search))
																		Pencarian: "{{ $search }}"
																@endif
																@if (!empty($mata_kuliah_id))
																		@php $selectedMk = $mataKuliahs->find($mata_kuliah_id); @endphp
																		@if ($selectedMk)
																				, Mata Kuliah: "{{ $selectedMk->nama }}"
																		@endif
																@endif
																@if (!empty($periode_id))
																		@php $selectedPeriode = $periodes->find($periode_id); @endphp
																		@if ($selectedPeriode)
																				, Periode: "{{ $selectedPeriode->tahun_ajaran }}"
																		@endif
																@endif
																@if (!empty($dosen_id))
																		@php $selectedDosen = $dosens->find($dosen_id); @endphp
																		@if ($selectedDosen)
																				, Dosen: "{{ $selectedDosen->name }}"
																		@endif
																@endif
																@if (!empty($peminatan_id))
																		@php $selectedPeminatan = $peminatans->find($peminatan_id); @endphp
																		@if ($selectedPeminatan)
																				, Peminatan: "{{ $selectedPeminatan->nama }}"
																		@endif
																@endif
														</div>
												@endif
										</form>
								</div>
						</div>
				</div>
		</div>

		<!-- Results Section -->
		@if (!$hasFilters)
				<!-- No Filter Applied State -->
				<div class="row mt-4">
						<div class="col-12">
								<div class="card shadow">
										<div class="card-body py-5 text-center">
												<div class="d-flex flex-column align-items-center">
														<i class="fas fa-search fa-4x text-muted mb-4"></i>
														<h4 class="text-muted mb-3">Mulai Pencarian Soal</h4>
														<p class="text-muted col-md-8 mb-4">
																Gunakan filter di atas untuk mencari soal berdasarkan mata kuliah, periode, dosen, peminatan,
																atau gunakan pencarian global untuk mencari berdasarkan nama file atau kata kunci lainnya.
														</p>
														<div class="row text-left">
																<div class="col-md-6">
																		<h6 class="text-primary"><i class="fas fa-lightbulb mr-2"></i>Tips Pencarian:</h6>
																		<ul class="text-muted small">
																				<li>Gunakan <strong>Pencarian Global</strong> untuk mencari berdasarkan kata kunci</li>
																				<li>Kombinasikan beberapa filter untuk hasil yang lebih spesifik</li>
																				<li>Filter <strong>Mata Kuliah</strong> akan menampilkan semua soal dari mata kuliah tersebut</li>
																		</ul>
																</div>
																<div class="col-md-6">
																		<h6 class="text-success"><i class="fas fa-filter mr-2"></i>Filter Tersedia:</h6>
																		<ul class="text-muted small">
																				<li><strong>{{ $mataKuliahs->count() }}</strong> Mata Kuliah</li>
																				<li><strong>{{ $periodes->count() }}</strong> Periode</li>
																				<li><strong>{{ $dosens->count() }}</strong> Dosen</li>
																				<li><strong>{{ $peminatans->count() }}</strong> Peminatan</li>
																		</ul>
																</div>
														</div>
												</div>
										</div>
								</div>
						</div>
				</div>
		@else
				<!-- Results Count -->
				<div class="row mt-4">
						<div class="col-12">
								<div class="d-flex justify-content-between align-items-center mb-3">
										<h5 class="mb-0 text-gray-800">
												<i class="fas fa-list mr-2"></i>Hasil Pencarian
										</h5>
										<small class="text-muted">
												@if ($soals->total() > 0)
														Menampilkan {{ $soals->firstItem() }} - {{ $soals->lastItem() }} dari {{ $soals->total() }} soal
												@else
														Tidak ada hasil ditemukan
												@endif
										</small>
								</div>
						</div>
				</div>

				<!-- Results Table -->
				<div class="row">
						<div class="col-12">
								<div class="card shadow">
										<div class="card-body">
												<div class="table-responsive">
														<table class="table-striped table-hover table">
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
																										@if ($soal->pengampu->mataKuliah->kode)
																												<br><small class="text-muted">{{ $soal->pengampu->mataKuliah->kode }}</small>
																										@endif
																										@if ($soal->pengampu->mataKuliah->peminatan)
																												<br><small
																														class="badge badge-info">{{ $soal->pengampu->mataKuliah->peminatan->nama }}</small>
																										@endif
																								</div>
																						</td>
																						<td>
																								@if ($soal->pengampu->periode)
																										{{ $soal->pengampu->periode->tahun_ajaran }}
																										<br><small class="text-muted">{{ ucfirst($soal->pengampu->periode->semester) }}</small>
																								@else
																										N/A
																								@endif
																						</td>
																						<td>
																								@if ($soal->pengampu->dosen)
																										<div class="d-flex align-items-center">
																												<div
																														class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2">
																														<i class="fas fa-user text-white"></i>
																												</div>
																												<span>{{ $soal->pengampu->dosen->name }}</span>
																										</div>
																								@else
																										<span class="text-muted">Tidak ada dosen</span>
																								@endif
																						</td>
																						<td>
																								@if ($soal->path)
																										<div class="d-flex align-items-center">
																												<i class="fas fa-file-pdf text-danger mr-2"></i>
																												<span class="text-truncate" style="max-width: 200px;"
																														title="{{ basename($soal->path) }}">
																														{{ basename($soal->path) }}
																												</span>
																										</div>
																										@if ($soal->created_at)
																												<small class="text-muted d-block">
																														Upload: {{ $soal->created_at->format('d M Y') }}
																												</small>
																										@endif
																										<span class="badge badge-{{ $soal->status == 'publish' ? 'success' : 'warning' }}">
																												{{ ucfirst($soal->status) }}
																										</span>
																								@else
																										<span class="text-muted">N/A</span>
																								@endif
																						</td>
																						<td>
																								@php
																										$user = Auth::user();
																										$canEdit = $user->role === 'admin' || $soal->pengampu->dosen_id === $user->id;
																								@endphp

																								@if ($soal->path && file_exists(storage_path('app/public/' . $soal->path)))
																										<div class="btn-group" role="group">
																												<a href="{{ asset('storage/' . $soal->path) }}" class="btn btn-sm btn-success"
																														target="_blank" title="Download {{ basename($soal->path) }}">
																														<i class="fas fa-download"></i>
																												</a>
																												@if ($canEdit)
																														<a href="{{ route('soal.edit', $soal->id) }}" class="btn btn-sm btn-warning"
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
																												@if ($canEdit)
																														<a href="{{ route('soal.edit', $soal->id) }}" class="btn btn-sm btn-warning"
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
																						<td colspan="6" class="py-4 text-center">
																								<div class="d-flex flex-column align-items-center">
																										<i class="fas fa-search fa-3x text-muted mb-3"></i>
																										<h5 class="text-muted">Tidak ada data yang sesuai dengan filter</h5>
																										<p class="text-muted">Coba ubah atau reset filter untuk melihat hasil lainnya</p>
																										<button type="button" class="btn btn-outline-primary" wire:click="resetFilters">
																												<i class="fas fa-sync-alt mr-2"></i>Reset Filter
																										</button>
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
				@if ($soals->hasPages())
						<div class="row mt-3">
								<div class="col-12 d-flex justify-content-center">
										{{ $soals->links() }}
								</div>
						</div>
				@endif
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
						background-color: rgba(0, 123, 255, .075);
				}

				.badge {
						font-size: 0.75em;
				}

				.card {
						transition: transform 0.2s;
				}

				.card:hover {
						transform: translateY(-2px);
				}
		</style>
@endpush
