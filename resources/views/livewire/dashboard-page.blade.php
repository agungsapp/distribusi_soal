<div class="container-fluid">

		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

		</div>

		<!-- Content Row -->
		<div class="row">

				<!-- Earnings (Monthly) Card Example -->
				<div class="col-xl-3 col-md-6 mb-4">
						<div class="card border-left-primary h-100 py-2 shadow">
								<div class="card-body">
										<div class="row no-gutters align-items-center">
												<div class="col mr-2">
														<div class="font-weight-bold text-primary text-uppercase mb-1 text-xs">
																Mata Kuliah</div>
														<div class="h5 font-weight-bold mb-0 text-gray-800">{{ $count['matkul'] }}</div>
												</div>
												<div class="col-auto">
														<i class="fas fa-calendar fa-2x text-gray-300"></i>
												</div>
										</div>
								</div>
						</div>
				</div>

				<!-- Earnings (Monthly) Card Example -->
				<div class="col-xl-3 col-md-6 mb-4">
						<div class="card border-left-success h-100 py-2 shadow">
								<div class="card-body">
										<div class="row no-gutters align-items-center">
												<div class="col mr-2">
														<div class="font-weight-bold text-success text-uppercase mb-1 text-xs">
																Dosen</div>
														<div class="h5 font-weight-bold mb-0 text-gray-800">{{ $count['dosen'] }}</div>
												</div>
												<div class="col-auto">
														<i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
												</div>
										</div>
								</div>
						</div>
				</div>



				<!-- Pending Requests Card Example -->
				<div class="col-xl-3 col-md-6 mb-4">
						<div class="card border-left-warning h-100 py-2 shadow">
								<div class="card-body">
										<div class="row no-gutters align-items-center">
												<div class="col mr-2">
														<div class="font-weight-bold text-warning text-uppercase mb-1 text-xs">
																Peminatan</div>
														<div class="h5 font-weight-bold mb-0 text-gray-800">{{ $count['peminatan'] }}</div>
												</div>
												<div class="col-auto">
														<i class="fas fa-comments fa-2x text-gray-300"></i>
												</div>
										</div>
								</div>
						</div>
				</div>
		</div>



</div>
