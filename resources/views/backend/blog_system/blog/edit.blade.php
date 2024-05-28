@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('News Information')}}</h5>
            </div>
            <div class="card-body">
                <form id="add_form" class="form-horizontal" action="{{ route('news.update',$blog->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('News Title')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('News Title')}}"  id="title" name="title" class="form-control"  value="{{ old('title', $blog->title) }}" >
                            @error('title')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                  
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('Arabic News Title')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Arabic News Title')}}" id="ar_title" name="ar_title"  dir="rtl"  value="{{ old('ar_title', $blog->ar_title) }}" class="form-control" >
                            @error('ar_title')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">
                            {{translate('Image')}} 
                            <small>(1920x1080)</small>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse')}}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="image" class="selected-files" value="{{ $blog->image }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{translate('Description')}}
                        </label>
                        <div class="col-md-9">
                            <textarea class="" id="engEditor" name="content">{{ old('content', $blog->content) }}</textarea>
                            @error('content')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{translate('Arabic Description')}}
                        </label>
                        <div class="col-md-9">
                            <textarea class=""  id="arEditor" name="ar_content" rtl="true">{{ old('ar_content', $blog->ar_content) }}</textarea>
                            @error('ar_content')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{translate('News Date')}}
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="news_date" id="news_date" placeholder="YYYY-MM-DD" class="form-control" value="{{ old('news_date', $blog->blog_date) }}" autocomplete="off">
                            @error('news_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mx-auto">
                        <h6 class="fw-600 mb-2 mt-2">{{ translate('Seo Fields') }}</h6>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Meta Title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title" value="{{ old('meta_title', $blog->seo_title) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Meta Description')}}</label>
                        <div class="col-sm-9">
                            <textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description">{{ old('meta_description', $blog->seo_description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Keywords')}}</label>
                        <div class="col-sm-9">
                            <textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="keywords">{{ old('keywords', $blog->keywords) }}</textarea>
                            <small class="text-muted">{{ translate('Separate with coma') }}</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('OG Title') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="{{ translate('OG Title') }}"
                                name="og_title" value="{{ old('og_title', $blog->og_title) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('OG Description') }}</label>
                        <div class="col-sm-9">
                            <textarea class="resize-off form-control" placeholder="{{ translate('OG Description') }}" name="og_description">{{ old('og_description', $blog->og_description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('Twitter Title') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="{{ translate('Twitter Title') }}"
                                name="twitter_title" value="{{ old('twitter_title', $blog->twitter_title) }}">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('Twitter Description') }}</label>
                        <div class="col-sm-9">
                            <textarea class="resize-off form-control" placeholder="{{ translate('Twitter Description') }}"
                                name="twitter_description">{{ old('twitter_description', $blog->twitter_description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Meta Image')}}</label>
                        <div class="col-sm-9">
                            <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="meta_image" class="selected-files" value="{{ $blog->meta_image }}">
                            </div>
                            <div class="file-preview">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">
                            {{translate('Save')}}
                        </button>
                        <a href="{{ route('news.index') }}" class="btn btn-info">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@endsection

@section('script')
<script src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
<script>
    // function makeSlug(val) {
    //     let str = val;
    //     let output = str.replace(/\s+/g, '-').toLowerCase();
    //     $('#slug').val(output);
    // }

    $("#news_date").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
</script>
<x-tiny-script :editors="[['id' => '#engEditor', 'dir' => 'ltr'], ['id' => '#arEditor', 'dir' => 'rtl']]" />
@endsection
