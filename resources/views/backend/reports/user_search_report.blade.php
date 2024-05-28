@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="h4">{{ translate('User Search Report') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Search Key') }}</th>
                                <th>{{ translate('User') }}</th>
                                <th>{{ translate('IP Address') }}</th>
                                <th>{{ translate('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($searches as $key => $searche)
                                <tr>
                                    <td>{{ $key + 1 + ($searches->currentPage() - 1) * $searches->perPage() }}</td>
                                    <td>{{ $searche->query }}</td>
                                    <td>
                                        @if ($searche->user_id)
                                            <a
                                                href="{{ route('user_search_report.index', ['user_id' => $searche->user_id]) }}">
                                                {{ $searche->user->name }}
                                            </a>
                                        @else
                                            GUEST
                                        @endif
                                    </td>
                                    <td>{{ $searche->ip_address }}</td>
                                    <td>{{ $searche->created_at->format('d-m-Y h:i:s A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination mt-4">
                        {{ $searches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
