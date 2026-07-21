@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-components.css') }}">
@endsection

<x-layout bodyClass="">

    <div>
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">
                    <!-- Navbar -->

                    <!-- End Navbar -->
                </div>
            </div>
        </div>
        <main class="main-content  mt-0">
            <section>
                <div class="page-header min-vh-100">
                    <div class="container">
                        <div class="row">
                            <div
                                class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
                                <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center"
                                    class="auth-bg-signup">
                                </div>
                            </div>
                            <div
                                class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                                <div class="card card-plain">
                                    <div class="card-header">
                                        <h4 class="font-weight-bolder">Show User</h4>
                                        {{-- <p class="mb-0"></p> --}}
                                    </div>
                                    <div class="card-body">

                                            <label class="form-label">Name</label>
                                            <div class="input-group input-group-outline mb-3">

                                                <p>{{$user->name}}</p>

                                            </div>
                                            <label class="form-label">Email</label>
                                            <div class="input-group input-group-outline mb-3">

                                                <p>{{$user->email}}</p>
                                                {{-- @error('email')
                                                <span class="text-danger">{{$message}}</span>

                                                @enderror --}}

                                            </div>
                                            {{-- <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Password</label>
                                                <input type="password" class="form-control" name="password" value="{{$user->password}}" required>
                                                @error('password')
                                                <span class="text-danger">{{$message}}</span>

                                                @enderror

                                            </div> --}}
                                            <label class="form-label">Roles</label>
                                            <div class="input-group input-group-outline mb-3">

                                                <p>
                                                    @foreach ($user->roles as $role )
                                                    {{$role->name}} {{!$loop->last ? ', ' : ''}}

                                                    @endforeach
                                                        </p>

                                            </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</x-layout>
