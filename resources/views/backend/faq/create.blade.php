@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('FAQ Information')}}</h5>
            </div>
            <div class="card-body">
                <form id="add_form" class="form-horizontal" action="{{ route('faq.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('Question')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Question')}}"  id="title" name="title" class="form-control"  value="{{ old('title') }}" >
                            @error('title')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('Arabic Question')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Arabic Question')}}" id="ar_title" name="ar_title"  dir="rtl"  value="{{ old('ar_title') }}" class="form-control" >
                            @error('ar_title')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{translate('Answer')}}
                        </label>
                        <div class="col-md-9">
                            <textarea class="" id="engEditor" name="content">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{translate('Arabic Answer')}}
                        </label>
                        <div class="col-md-9">
                            <textarea class=""  id="arEditor" name="ar_content" rtl="true">{{ old('ar_content') }}</textarea>
                            @error('ar_content')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('Sort Order')}}
                        </label>
                        <div class="col-md-9">
                            <input type="number"  id="sort_order" name="sort_order" value="{{ old('sort_order',0) }}" class="form-control" >
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">
                            {{translate('Save')}}
                        </button>
                        <a href="{{ route('faq.index') }}" class="btn btn-info">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')

<script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
<script>
    
</script>
<x-tiny-script :editors="[['id' => '#engEditor', 'dir' => 'ltr'], ['id' => '#arEditor', 'dir' => 'rtl']]" />
@endsection
