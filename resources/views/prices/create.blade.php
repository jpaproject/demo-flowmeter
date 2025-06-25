@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Harga Per M3</h4>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!--end card-header-->
            <div class="card-body pt-0">
                @php
                $harga = \App\Models\TotalizerPrice::first();
                @endphp

                <form id="form-validation-2" class="form" action="{{ route('totalizer-prices.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label for="price" class="form-label">Harga Per M3</label>
                        <input class="form-control" type="number" id="price" name="price" min="0" step="any"
                            placeholder="Masukkan Harga Per M3" value="{{ old('price', $harga->price ?? '') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        {{ $harga ? 'Update' : 'Create' }}
                    </button>
                </form>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>
<!--end row-->
@endsection
