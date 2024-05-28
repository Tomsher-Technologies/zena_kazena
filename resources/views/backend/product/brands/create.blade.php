@extends('backend.layouts.app')

@section('content')
    <style>
        .remove-attachment {
            display: none;
        }
    </style>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Brand Information')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                	@csrf

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Name')}}<span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" onchange="title_update(this)" value="{{ old('name') }}">
                            @error('name')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Slug<span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="Slug" id="slug" name="slug" class="form-control" value="{{ old('slug') }}">
                            @error('slug')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Logo') }}
                            <small>({{ translate('120x80') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="logo" value="" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group  row">
                        <label class="col-md-3 col-form-label">Active Status</label>
                        <div class="col-md-9">
                            <select class="select2 form-control" name="status">
                                <option {{ old('status') == 1 ? 'selected' : '' }} value="1">Yes
                                </option>
                                <option {{ old('status') == 0 ? 'selected' : '' }} value="0">No
                                </option>
                            </select>
                        </div>
                    </div>

                   <h5 class="mb-0 h6">{{translate('SEO Section')}}</h5>
                    <hr>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">Meta Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title"
                                placeholder="Meta Title" value="{{ old('meta_title') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">Meta Description</label>
                        <div class="col-md-9">
                            <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">Meta Keywords</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_keywords" placeholder="Meta Keywords" value="{{ old('meta_keywords') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">OG Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="og_title" placeholder="OG Title" value="{{ old('og_title') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">OG Description</label>
                        <div class="col-md-9">
                            <textarea name="og_description" rows="5" class="form-control">{{ old('og_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">Twitter Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="twitter_title" placeholder="Twitter Title" value="{{ old('twitter_title') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">Twitter Description</label>
                        <div class="col-md-9">
                            <textarea name="twitter_description" rows="5" class="form-control">{{ old('twitter_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        <a href="{{ route('brands.index') }}" class="btn btn-sm btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
    function title_update(e) {
        title = e.value;
        title = title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')
        $('#slug').val(title)
    }
    </script>
@endsection
