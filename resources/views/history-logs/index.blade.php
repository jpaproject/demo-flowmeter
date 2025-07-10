@extends('layouts.main')
@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Manual Entry Totalizer</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('history-logs.create') }}" class="btn btn-success btn-sm">
                            + Add Totalizer
                        </a>
                    </div>
                </div>
                
                <!--end row-->
            </div>
            <!--end card-header-->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table datatable" id="datatable_1">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Totalizer Value</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historyLogs as $historyLog)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('Y-m-d', strtotime($historyLog->created_at)) }}</td>
                                    <td>{{ date('H:i', strtotime($historyLog->created_at)) }}</td>
                                    <td>{{ $historyLog->totalizer }}</td>
                                    <td>
                                        <a href="{{ route('history-logs.edit', $historyLog->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('history-logs.destroy', $historyLog->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this totalizer?')">Delete</button>
                                            </form>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>
<!--end row-->
@endsection