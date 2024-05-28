@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h5 class="h4">{{ translate('Website Pages') }}</h5>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h6 class="mb-0 fw-600">{{ translate('All Pages') }}</h6>
		{{-- <a href="{{ route('custom-pages.create') }}" class="btn btn-primary">{{ translate('Add New Page') }}</a> --}}
	</div>
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th >#</th>
                <th>{{translate('Name')}}</th>
                <th class="text-center">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
        	@foreach ($pages as $key => $page)
        	<tr>
        		<td>{{ $key+1 }}</td>
        		
				<td>
					{{ $page->slug }}
				</td>
				
        		<td class="text-center">
					@if($page->type == 'home')
						<a href="{{route('custom-pages.edit', ['id'=>$page->type, 'lang'=>env('DEFAULT_LANGUAGE'), 'page'=>'home'] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
							<i class="las la-pen"></i>
						</a>
					@else
	          			<a href="{{route('custom-pages.edit', ['id'=>$page->type, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
							<i class="las la-pen"></i>
						</a>
					@endif
			
        		</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
	</div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
