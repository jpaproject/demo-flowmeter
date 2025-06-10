@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Edit Device</h4>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <form id="form-validation-2" class="form" action="{{ route('devices.update', $device->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- This line is crucial for Laravel to recognize it as an update request --}}

                    <div class="mb-2">
                        <label for="display_name" class="form-label">Display Name</label>
                        <input class="form-control" type="text" id="display_name" name="display_name" value="{{ old('display_name', $device->display_name) }}" placeholder="Enter Display Name">
                        @error('display_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="name" class="form-label">Key</label>
                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $device->name) }}" placeholder="Enter Name">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="area_id" class="form-label">Area</label>
                        <select class="form-select" id="area_id" name="area_id">
                            <option value="">Select Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $device->area_id) == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description">{{ old('description', $device->description) }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('devices.index') }}" class="btn btn-secondary">Cancel</a>
                </form><!--end form-->
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
</div><!--end row-->
@endsection
