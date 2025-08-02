<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Semua Mata Kuliah</h1>
		</div>

		<!-- Search -->
		<div class="row mb-3">
				<div class="col-md-4">
						<input type="text" class="form-control" placeholder="Cari nama, kode, atau prodi..." wire:model.live="search">
				</div>
		</div>

		<!-- Content Row -->
		<div class="row">
				@forelse ($pelajarans as $item)
						<div class="col-12 col-md-4 col-lg-3 mb-3">
								<div class="card shadow">
										<img src="{{ asset('local/image/bg-mata-kuliah.jpeg') }}" class="card-img-top" alt="bg-mata-kuliah">
										<div class="card-body">
												<h5 class="card-title" style="min-height: 50px;">{{ $item->kode . ' | ' . $item->nama }}</h5>
												<p class="card-text">Dosen Pengampu:</p>
												<ul style="min-height: 70px;">
														@forelse ($item->pengampu as $p)
																<li>{{ $p->dosen ? $p->dosen->name : '-' }}</li>
														@empty
																<li>-- belum ada dosen pengampu --</li>
														@endforelse
												</ul>
												<a href="{{ route('manage-mata-kuliah', $item->id) }}" class="btn btn-primary">Manage</a>
										</div>
								</div>
						</div>
				@empty
						<div class="col-12">
								<div class="alert alert-warning">Tidak ada mata kuliah yang ditemukan.</div>
						</div>
				@endforelse
		</div>

		<!-- Pagination -->
		<div class="row">
				<div class="col-12">
						{{ $pelajarans->links() }}
				</div>
		</div>
</div>
