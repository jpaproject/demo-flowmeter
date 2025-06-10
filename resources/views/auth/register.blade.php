<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="light">


<head>


    <meta charset="utf-8" />
    <title>EH Trouble Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />



    <!-- App css -->
    <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets')}}/css/app.min.css" rel="stylesheet" type="text/css" />

</head>


<!-- Top Bar Start -->

<body>
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body pt-0">
                                    <form class="my-4" action="{{ route('register') }}" method="POST">
                                        @csrf
                                    
                                        {{-- Global Error Message (optional) --}}
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    
                                        {{-- Name --}}
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="name">Name</label>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   id="name"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   placeholder="Enter name">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        {{-- Email --}}
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   id="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   placeholder="Enter email">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        {{-- Password --}}
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="password">Password</label>
                                            <input type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   name="password"
                                                   id="password"
                                                   placeholder="Enter password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        {{-- Confirm Password --}}
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                                            <input type="password"
                                                   class="form-control"
                                                   name="password_confirmation"
                                                   id="password_confirmation"
                                                   placeholder="Enter confirm password"
                                                   required autocomplete="new-password">
                                        </div>
                                    
                                        {{-- Submit --}}
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit">
                                                        Sign Up <i class="fas fa-sign-in-alt ms-1"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <!--end form-->
                                    <div class="text-center">
                                        <p class="text-muted">Already have an account ? <a href="{{ route('login') }}"
                                                class="text-primary ms-2">Log in</a></p>
                                    </div>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div><!-- container -->
</body>
<!--end body-->

</html>
