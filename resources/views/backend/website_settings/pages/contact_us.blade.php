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
            <label class="col-sm-2 col-from-label" for="name">{{translate('Address')}}</label>
            <div class="col-sm-10">
                <textarea class="resize-off form-control" placeholder="{{translate('Address')}}" name="content" @if($lang == 'ae') dir="rtl" @endif>{!! $page->getTranslation('content',$lang) !!}</textarea>
            </div>
        </div>

        <div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Whatsapp')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Whatsapp')}}" name="heading1" value="{{ $page->getTranslation('heading1',$lang) }}" required @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>
		</div>

        <div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Phone Number')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Phone Number')}}" name="heading2" value="{{ $page->getTranslation('heading2',$lang) }}" required @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>
		</div>

        <div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Email')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Email')}}" name="heading3" value="{{ $page->getTranslation('heading3',$lang) }}" required @if($lang == 'ae') dir="rtl" @endif>
				</div>
			</div>
		</div>

        <div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Form Heading')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Form Heading')}}" name="heading4" value="{{ $page->getTranslation('heading4',$lang) }}" required @if($lang == 'ae') dir="rtl" @endif>
				</div>
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
