@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">                      
                        <h4 class="card-title">Edit Area</h4>                      
                    </div><!--end col-->
                </div>  <!--end row-->                                  
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <form class="form" action="{{ route('areas.update', $area->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label for="name" class="form-label">Name</label>
                        <input class="form-control" type="text" id="name" name="name" value="{{ $area->name }}" placeholder="Enter Name">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form><!--end form-->            
            </div><!--end card-body--> 
        </div><!--end card--> 
    </div> <!--end col-->                                                                                
</div><!--end row-->
@endsection
