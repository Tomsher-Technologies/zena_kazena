@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Start: error page -->
                <div class=" content-center">
                    <div class="error-page text-center">
                        <img src="{{ asset('assets/img/403.svg') }}" alt="403" class="svg" width="40%">
                        {{-- <div class="error-page__title">403</div> --}}
                        <h5 class="fw-500">SORRY! YOU DON'T HAVE THE RIGHT PERMISSIONS</h5>
                        
                    </div>
                </div>
                <!-- End: error page -->
            </div>
        </div>
    </div>
@endsection