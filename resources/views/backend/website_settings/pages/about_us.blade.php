@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Edit Page Information') }}</h1>
		</div>
	</div>
</div>
<div class="card">
	<ul class="nav nav-tabs nav-fill border-light">
		@foreach (\App\Models\Language::all() as $key => $language)
			<li class="nav-item">
				<a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('custom-pages.edit', ['id'=>$page->type, 'lang'=> $language->code] ) }}">
					<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
					<span>{{$language->name}}</span>
				</a>
			</li>
		@endforeach
	</ul>

	<form class="p-4" action="{{ route('custom-pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="_method" value="PATCH">
		<input type="hidden" name="lang" value="{{ $lang }}">

		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Page Content') }}</h6>
		</div>
		<div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Title')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="title" value="{{ $page->getTranslation('title',$lang) }}" required @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>
		</div>

        <div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Sub Title')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Sub Title')}}" name="sub_title" value="{{ $page->getTranslation('sub_title',$lang) }}" required @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>
		</div>

        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="name">{{translate('Content')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
            <div class="col-sm-10">
                <textarea class="form-control" placeholder="{{translate('Content')}}" name="content"  @if($lang == 'ae') dir="rtl" id="arEditor" @else  id="engEditor" @endif>{!! $page->getTranslation('content',$lang) !!}</textarea>
            </div>
        </div>

        <div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Heading 1')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Heading 1')}}" name="heading1" value="{{ $page->getTranslation('heading1',$lang) }}" required @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>
		</div>

        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="name">{{translate('Content 1')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
            <div class="col-sm-10">
                <textarea class="form-control" placeholder="{{translate('Content 1')}}" name="content1"  @if($lang == 'ae') dir="rtl" id="arEditor1" @else  id="engEditor1" @endif>{!! $page->getTranslation('content1',$lang) !!}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2 col-form-label" for="signinSrEmail">
                {{translate('Image')}} 
                <small>(577x525)</small>
            </label>
            <div class="col-md-10">
                <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse')}}
                        </div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="image1" class="selected-files" value="{{ $page->getTranslation('image1',$lang) }}">
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>

        <div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Heading 2')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Heading 2')}}" name="heading2" value="{{ $page->getTranslation('heading2',$lang) }}" required @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>
		</div>

        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="name">{{translate('Content 2')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
            <div class="col-sm-10">
                <textarea class="form-control" placeholder="{{translate('Content 2')}}" name="content2"  @if($lang == 'ae') dir="rtl" id="arEditor2" @else  id="engEditor2" @endif>{!! $page->getTranslation('content2',$lang) !!}</textarea>
            </div>
        </div>
       
		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Seo Fields') }}</h6>
		</div>
		<div class="card-body px-0">

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Title')}}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title" value="{{ $page->getTranslation('meta_title',$lang) }}" @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Description')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description" @if($lang == 'ae') dir="rtl" @endif>{!! $page->getTranslation('meta_description',$lang) !!}</textarea>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Keywords')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="keywords" @if($lang == 'ae') dir="rtl" @endif>{!! $page->getTranslation('keywords',$lang) !!}</textarea>
					<small class="text-muted">{{ translate('Separate with coma') }}</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{ translate('OG Title') }}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{ translate('OG Title') }}" name="og_title" value="{!! $page->getTranslation('og_title',$lang) !!}" @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{ translate('OG Description') }}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{ translate('OG Description') }}" name="og_description" @if($lang == 'ae') dir="rtl" @endif>{!! $page->getTranslation('og_description',$lang) !!}</textarea>
				</div>
			</div>


			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{ translate('Twitter Title') }}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{ translate('Twitter Title') }}" name="twitter_title" value="{!! $page->getTranslation('twitter_title',$lang) !!}" @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{ translate('Twitter Description') }}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{ translate('Twitter Description') }}"
						name="twitter_description" @if($lang == 'ae') dir="rtl" @endif>{!! $page->getTranslation('twitter_description',$lang) !!}</textarea>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Image')}}</label>
				<div class="col-sm-10">
					<div class="input-group " data-toggle="aizuploader" data-type="image">
							<div class="input-group-prepend">
								<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
						</div>
						<div class="form-control file-amount">{{ translate('Choose File') }}</div>
						<input type="hidden" name="meta_image" class="selected-files" value="{!! $page->getTranslation('meta_image',$lang) !!}">
					</div>
					<div class="file-preview">
					</div>
				</div>
			</div>

			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
				<a href="{{ route('website.pages') }}" class="btn btn-info">Cancel</a>
			</div>
		</div>
	</form>
</div>
@endsection

@section('script')

<script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
<script>
    
</script>
<x-tiny-script :editors="[['id' => '#engEditor', 'dir' => 'ltr'],['id' => '#engEditor1', 'dir' => 'ltr'],['id' => '#engEditor2', 'dir' => 'ltr'], ['id' => '#arEditor', 'dir' => 'rtl'], ['id' => '#arEditor1', 'dir' => 'rtl'], ['id' => '#arEditor2', 'dir' => 'rtl']]" />
@endsection