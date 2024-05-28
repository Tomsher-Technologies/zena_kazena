@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All Shops') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('admin.shops.create') }}" class="btn btn-primary">
                    <span>Add New Shop</span>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_sellers" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('Shops') }}</h5>
                </div>

                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{ translate('Bulk Action') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="bulk_delete()">{{ translate('Delete selection') }}</a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search"
                            name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type name or email & Enter') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <!--<th data-breakpoints="lg">#</th>-->
                            <th>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th>{{ translate('Name') }}</th>
                            <th data-breakpoints="lg">{{ translate('Phone') }}</th>
                            <th data-breakpoints="lg">{{ translate('Email Address') }}</th>
                            <th width="10%">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shops as $key => $shop)
                            @if ($shop->user != null)
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]"
                                                        value="{{ $shop->id }}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($shop->status == 0)
                                            <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                        @endif
                                        {{ $shop->name }}
                                    </td>
                                    <td>{{ $shop->phone }}</td>
                                    <td>{{ $shop->email }}</td>

                                    <td class="text-right">

                                        <a href="{{ route('admin.shops.edit', $shop) }}"
                                            class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm" title="{{ translate('Delete') }}" onclick="single_delete({{$shop->id}})"> <i class="las la-trash"></i>
                                        </a>


                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $shops->appends(request()->input())->links() }}
                </div>
            </div>
            </from>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function sort_sellers(el) {
            $('#sort_sellers').submit();
        }

        function bulk_delete() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    var data = new FormData($('#sort_sellers')[0]);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('bulk-shop-delete') }}",
                        type: 'POST',
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire(
                                    'Deleted!',
                                    'Successfully deleted.',
                                    'success'
                                )
                                setTimeout(function () {
                                    location.reload();
                                }, 400);
                            }
                        }
                    });
                }
            })
        }

        function single_delete(shop_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                  
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('admin.shops.delete') }}",
                        type: 'POST',
                        data: 'id='+ shop_id,
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire(
                                    'Deleted!',
                                    'Successfully deleted.',
                                    'success'
                                )
                                setTimeout(function () {
                                    location.reload();
                                }, 400);
                            }
                        }
                    });
                }
            })
        }
    </script>
@endsection
