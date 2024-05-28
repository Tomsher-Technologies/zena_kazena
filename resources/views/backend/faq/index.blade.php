@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h5 class="h4">{{translate('All FAQ')}}</h5>
        </div>
        <div class="col text-right">
            <a href="{{ route('faq.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Question')}}</span>
            </a>
        </div>
    </div>
</div>
<br>

<div class="card">
  
        <div class="card-body">
            <table class="table mb-0 aiz-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="w-50">{{translate('Question')}}</th>
                        <th>{{translate('Sort Order')}}</th>
                        <th>{{translate('Status')}}</th>
                        <th class="text-center">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $key => $faq)
                    <tr>
                        <td>
                            {{ ($key+1) + ($faqs->currentPage() - 1) * $faqs->perPage() }}
                        </td>
                        <td>
                            {{ $faq->title }}
                        </td>
                        <td>
                            {{ $faq->sort_order }}
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="change_status(this)" value="{{ $faq->id }}" <?php if($faq->status == 1) echo "checked";?>>
                                <span></span>
                            </label>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="{{ route('faq.edit',$faq->id)}}" title="{{ translate('Edit') }}">
                                <i class="las la-pen"></i>
                            </a>
                            
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('faq.destroy', $faq->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $faqs->links() }}
            </div>
        </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')

    <script type="text/javascript">
        function change_status(el){
            var status = 0;
            if(el.checked){
                var status = 1;
            }
            $.post('{{ route('faq.change-status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('FAQ status changed successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>

@endsection
