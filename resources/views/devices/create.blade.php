@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">                      
                        <h4 class="card-title">Add new device</h4>                      
                    </div>
                </div>                                
            </div>
            <div class="card-body pt-0">
                <form id="form-validation-2" class="form" action="{{ route('devices.store') }}" method="POST">
                    @csrf

                    <div class="mb-2">
                        <label for="display_name" class="form-label">Display Name</label>
                        <input class="form-control" type="text" id="display_name" name="display_name" placeholder="Enter Display Name">
                    </div>

                    <div class="mb-2">
                        <label for="name" class="form-label">Key</label>
                        <input class="form-control" type="text" id="name" name="name" placeholder="Enter Name">
                    </div>

                    <div class="mb-2">
                        <label for="area_id" class="form-label">Area</label>
                        <select class="form-select" id="area_id" name="area_id">
                            <option value="">Select Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                        <input class="form-control" type="text" id="nama_pelanggan" name="nama_pelanggan" placeholder="Masukkan Nama Pelanggan">
                    </div>

                    <div class="mb-2">
                        <label for="nomor_pelanggan" class="form-label">Nomor Pelanggan</label>
                        <input class="form-control" type="text" id="nomor_pelanggan" name="nomor_pelanggan" placeholder="Masukkan Nomor Pelanggan">
                    </div>

                    <div class="mb-2">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>           
            </div>
        </div>
    </div>                                                                                 
</div>
@endsection
