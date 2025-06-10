@extends('layouts.main')
@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                        <div class="d-flex align-items-center flex-row flex-wrap">
                            <div class="position-relative me-3">
                                <img src="assets/images/users/avatar-7.jpg" alt="" height="120" class="rounded-circle">
                            </div>
                            <div class="">
                                <h5 class="fw-semibold fs-22 mb-1">{{ ucfirst($user->name) }}</h5>                                                        
                                <p class="mb-0 text-muted fw-medium">{{ $user->role ?? '' }}</p>                                                        
                            </div>
                        </div>                                                
                    </div><!--end col-->
                </div><!--end row-->               
            </div><!--end card-body--> 
        </div><!--end card--> 
    </div> <!--end col-->                                  
</div><!--end row-->

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">                      
                        <h4 class="card-title">Personal Information</h4>                      
                    </div><!--end col-->
                </div>  <!--end row-->                                  
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <ul class="list-unstyled mb-0">                                        
                    <li class=""><i class="las la-birthday-cake me-2 text-secondary fs-22 align-middle"></i> <b> Birth Date </b> : 06 June 1989</li>
                    <li class="mt-2"><i class="las la-briefcase me-2 text-secondary fs-22 align-middle"></i> <b> Position </b> : Full Stack Developer</li>
                    <li class="mt-2"><i class="las la-university me-2 text-secondary fs-22 align-middle"></i> <b> Education </b> : Stanford Univercity</li>
                    <li class="mt-2"><i class="las la-language me-2 text-secondary fs-22 align-middle"></i> <b> Languages </b> : English, French, Spanish</li>
                    <li class="mt-2"><i class="las la-phone me-2 text-secondary fs-22 align-middle"></i> <b> Phone </b> : +91 23456 78910</li>
                    <li class="mt-2"><i class="las la-envelope text-secondary fs-22 align-middle me-2"></i> <b> Email </b> : mannat.theme@gmail.com</li>
                </ul> 
            </div><!--end card-body--> 
        </div><!--end card--> 
    </div> <!--end col--> 
    <div class="col-md-8">
        <!-- Tab panes -->
        <div class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">                      
                                <h4 class="card-title">Personal Information</h4>                      
                            </div><!--end col-->                                                       
                        </div>  <!--end row-->                                  
                    </div><!--end card-header-->
                    <div class="card-body pt-0">                       
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">First Name</label>
                            <div class="col-lg-9 col-xl-8">
                                <input class="form-control" type="text" value="Rosa">
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Last Name</label>
                            <div class="col-lg-9 col-xl-8">
                                <input class="form-control" type="text" value="Dodson">
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Company Name</label>
                            <div class="col-lg-9 col-xl-8">
                                <input class="form-control" type="text" value="MannatThemes">
                                <span class="form-text text-muted font-12">We'll never share your email with anyone else.</span>
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Contact Phone</label>
                            <div class="col-lg-9 col-xl-8">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="las la-phone"></i></span>
                                    <input type="text" class="form-control" value="+123456789" placeholder="Phone" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Email Address</label>
                            <div class="col-lg-9 col-xl-8">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="las la-at"></i></span>
                                    <input type="text" class="form-control" value="rosa.dodson@demo.com" placeholder="Email" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Website Link</label>
                            <div class="col-lg-9 col-xl-8">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="la la-globe"></i></span>
                                    <input type="text" class="form-control" value=" https://mannatthemes.com/" placeholder="Email" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">USA</label>
                            <div class="col-lg-9 col-xl-8">
                                <select class="form-select">
                                    <option>London</option>
                                    <option>India</option>
                                    <option>USA</option>
                                    <option>Canada</option>
                                    <option>Thailand</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-9 col-xl-8 offset-lg-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-danger">Cancel</button>
                            </div>
                        </div>                                                    
                    </div><!--end card-body-->                                            
                </div><!--end card-->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div><!--end card-header-->
                    <div class="card-body pt-0"> 
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Current Password</label>
                            <div class="col-lg-9 col-xl-8">
                                <input class="form-control" type="password" placeholder="Password">
                                <a href="#" class="text-primary font-12">Forgot password ?</a>
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">New Password</label>
                            <div class="col-lg-9 col-xl-8">
                                <input class="form-control" type="password" placeholder="New Password">
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Confirm Password</label>
                            <div class="col-lg-9 col-xl-8">
                                <input class="form-control" type="password" placeholder="Re-Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-9 col-xl-8 offset-lg-3">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                                <button type="button" class="btn btn-danger">Cancel</button>
                            </div>
                        </div>   
                    </div><!--end card-body-->
                </div><!--end card-->
        </div> 
    </div> <!--end col-->                                                       
</div><!--end row-->
@endsection