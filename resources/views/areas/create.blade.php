@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">                      
                        <h4 class="card-title">Add new area</h4>                      
                    </div><!--end col-->
                </div>  <!--end row-->                                  
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <form id="form-validation-2" class="form" action="{{ route('areas.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label for="name" class="form-label">Name</label>
                        <input class="form-control" type="text" id="name" name="name" placeholder="Enter Name">
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form><!--end form-->            
            </div><!--end card-body--> 
        </div><!--end card--> 
    </div> <!--end col-->                                                                                
</div><!--end row-->
@endsection