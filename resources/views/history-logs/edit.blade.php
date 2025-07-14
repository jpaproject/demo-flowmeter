@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">                      
                        <h4 class="card-title">Edit Totalizer</h4>                      
                    </div>
                </div>                                  
            </div>
            <div class="card-body pt-0">
                <form action="{{ route('history-logs.update', $historyLog->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-2">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input class="form-control" 
                               type="date" 
                               id="tanggal" 
                               name="tanggal" 
                               value="{{ \Carbon\Carbon::parse($historyLog->created_at)->format('Y-m-d') }}" 
                               required>
                    </div>

                    <div class="mb-2">
                        <label for="jam" class="form-label">Jam</label>
                        <input class="form-control" 
                               type="time" 
                               id="jam" 
                               name="jam" 
                               value="{{ \Carbon\Carbon::parse($historyLog->created_at)->format('H:i') }}" 
                               required>
                    </div>

                    <div class="mb-2">
                        <label for="totalizer" class="form-label">Totalizer</label>
                        <input class="form-control" 
                               type="text" 
                               id="totalizer" 
                               name="totalizer" 
                               value="{{ $historyLog->totalizer }}" 
                               required>
                    </div>

                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('history-logs.index') }}" class="btn btn-secondary">Cancel</a>
                </form>         
            </div>
        </div>
    </div>                                                                                 
</div>
@endsection
