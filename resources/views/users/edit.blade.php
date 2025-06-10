@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">                      
                        <h4 class="card-title">Edit User</h4>                      
                    </div><!--end col-->
                </div>  <!--end row-->                                  
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <form class="form" action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" type="text" id="username" name="name" value="{{ old('name', $user->name) }}" placeholder="Enter Username">
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" type="text" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Enter email">
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label">New Password <small class="text-muted">(Optional)</small></label>
                        <input class="form-control" type="password" id="password" name="password" placeholder="Enter new password">
                    </div>
                    <div class="mb-3">
                        <label for="password2" class="form-label">Confirm New Password</label>
                        <input class="form-control" type="password" id="password2" name="password_confirmation" placeholder="Enter password again">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form><!--end form-->            
            </div><!--end card-body--> 
        </div><!--end card--> 
    </div> <!--end col-->                                                                                
</div><!--end row-->
@endsection
