@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">                      
                        <h4 class="card-title">Add Totalizer</h4>                      
                    </div>
                </div>                                
            </div>
            <div class="card-body pt-0">
                <form action="{{ route('history-logs.store') }}" method="POST">
                    @csrf

                    <div class="mb-2">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input class="form-control" type="date" id="tanggal" name="tanggal" required>
                    </div>

                    <div class="mb-2">
                        <label for="jam" class="form-label">Jam</label>
                        <input class="form-control" type="time" id="jam" name="jam" required>
                    </div>

                    <div class="mb-2">
                        <label for="totalizer" class="form-label">Totalizer (m³)</label>
                        <input class="form-control" type="text" id="totalizer" name="totalizer" placeholder="Contoh: 1,244.411133" required>
                    </div>

                    {{-- Tambahkan created_at manual jika perlu --}}
                    {{-- <input type="hidden" name="created_at" value="{{ now() }}"> --}}

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>          
            </div> 
        </div> 
    </div>                                                                                
</div>
@endsection
