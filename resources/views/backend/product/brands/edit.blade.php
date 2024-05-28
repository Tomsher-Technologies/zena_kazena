@extends('backend.layouts.app')

@section('content')
    <style>
        .remove-attachment {
            display: none;
        }
    </style>

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h4">{{ translate('Brand Information') }}</h5>
    </div>

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill border-light">
                    @foreach (\App\Models\Language::all() as $key => $language)
                        <li class="nav-item">
                            <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                href="{{ route('brands.edit', ['id' => $brand->id, 'lang' => $language->code]) }}">
                                <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11"
                                    class="mr-1">
                                <span>{{ $language->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <form class="p-4" action="{{ route('brands.update', $brand->id) }}" method="POST"
                    enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    @csrf

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Name') }} <i
                                class="las la-language text-danger" title="{{ translate('Translatable') }}"></i></label>
                        <div class="col-md-9">
                            <input type="text" name="name" value="{{ $brand->getTranslation('name', $lang) }}" class="form-control" id="name" onchange="title_update(this)" placeholder="{{ translate('Name') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Slug<span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" placeholder="Slug" id="slug" name="slug" class="form-control" value="{{ $brand->getTranslation('slug', $lang)}}">
                            @error('slug')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if ($lang == 'en')
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
                                    <input type="hidden" name="logo" value="{{ $brand->logo }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-md-3 col-form-label">Active Status</label>
                            <div class="col-md-9">
                                <select class="select2 form-control" name="status">
                                    <option {{ old('status', $brand->is_active) == 1 ? 'selected' : '' }} value="1">Yes
                                    </option>
                                    <option {{ old('status', $brand->is_active) == 0 ? 'selected' : '' }} value="0">No
                                    </option>
                                </select>
                            </div>
                        </div>
                    @endif

                    <h5 class="mb-0 h6">{{translate('SEO Section')}}</h5>
                    <hr>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">Meta Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title"
                                placeholder="Meta Title" value="{{ old('meta_title', $brand->getTranslation('meta_title', $lang)) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">Meta Description</label>
                        <div class="col-md-9">
                            <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description', $brand->getTranslation('meta_description', $lang)) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">Meta Keywords</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_keywords"
                                placeholder="Meta Keywords" value="{{ old('meta_keywords', $brand->getTranslation('meta_keywords', $lang)) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">OG Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="og_title"
                                placeholder="OG Title" value="{{ old('og_title', $brand->getTranslation('og_title', $lang)) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">OG Description</label>
                        <div class="col-md-9">
                            <textarea name="og_description" rows="5" class="form-control">{{ old('og_description', $brand->getTranslation('og_description', $lang)) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">Twitter Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="twitter_title"
                                placeholder="Twitter Title" value="{{ old('twitter_title', $brand->getTranslation('twitter_title', $lang)) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">Twitter Description</label>
                        <div class="col-md-9">
                            <textarea name="twitter_description" rows="5" class="form-control">{{ old('twitter_description', $brand->getTranslation('twitter_description', $lang)) }}</textarea>
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
        var title = e.value;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('generate-slug') }}",
            type: 'GET',
            data: {
                title: title
            },
            success: function(response) {
                $('#slug').val(response);
            }
        });
    }
    </script>
@endsection

