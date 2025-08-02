<div class="container">
		<!-- Outer Row -->
		<div class="row justify-content-center">
				<div class="col-xl-10 col-lg-12 col-md-9">
						<div class="card o-hidden my-5 border-0 shadow-lg">
								<div class="card-body p-0">
										<!-- Nested Row within Card Body -->
										<div class="row">
												<div class="col-lg-6 d-none d-lg-block bg-login-image">
														<div class="d-flex justify-content-center align-items-center" style="height: 100%; width: 100%;">
																<img src="https://www.darmajaya.ac.id/wp-content/uploads/4-6-14-thumbs.png" alt="Logo Darmajaya"
																		class="img-fluid" style="width: 100%; object-fit: contain;">
														</div>
												</div>
												<div class="col-lg-6">
														<div class="p-5">
																<div class="text-center">
																		<h1 class="h4 mb-4 text-gray-900">Welcome Back!</h1>
																</div>
																<form class="user" wire:submit.prevent="login">
																		<div class="form-group">
																				<input type="email" class="form-control form-control-user" id="email" wire:model="email"
																						placeholder="Enter Email Address...">
																				@error('email')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																		<div class="form-group">
																				<input type="password" class="form-control form-control-user" id="password" wire:model="password"
																						placeholder="Password">
																				@error('password')
																						<span class="text-danger small">{{ $message }}</span>
																				@enderror
																		</div>
																		<div class="form-group">
																				<div class="custom-control custom-checkbox small">
																						<input type="checkbox" class="custom-control-input" id="remember" wire:model="remember">
																						<label class="custom-control-label" for="remember">Remember Me</label>
																				</div>
																		</div>
																		<button type="submit" class="btn btn-primary btn-user btn-block">
																				Login
																		</button>
																</form>
																@if (session()->has('error'))
																		<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
																				{{ session('error') }}
																				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																						<span aria-hidden="true">&times;</span>
																				</button>
																		</div>
																@endif
														</div>
												</div>
										</div>
								</div>
						</div>
				</div>
		</div>
</div>
