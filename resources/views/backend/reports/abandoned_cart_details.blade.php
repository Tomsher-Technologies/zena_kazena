@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="h4">{{ translate('Abandoned Cart Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered aiz-table invoice-summary">
                                <thead>
                                    <tr class="bg-trans-dark">
                                        <th data-breakpoints="lg" class="min-col">#</th>
                                        <th width="10%">{{ translate('Photo') }}</th>
                                        <th class="text-uppercase">{{ translate('Description') }}</th>
                                        <th data-breakpoints="lg" class="min-col text-center text-uppercase">
                                            {{ translate('Date added') }}</th>
                                        <th data-breakpoints="lg" class="min-col text-center text-uppercase">
                                            {{ translate('Qty') }}</th>
                                        <th data-breakpoints="lg" class="min-col text-center text-uppercase">
                                            {{ translate('Price') }}</th>
                                        <th data-breakpoints="lg" class="min-col text-right text-uppercase">
                                            {{ translate('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($carts as $key => $cart)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route('auction-product', $cart->product->slug) }}"
                                                    target="_blank">
                                                    <img height="50"
                                                        src="{{ uploaded_asset($cart->product->thumbnail_img) }}"></a>
                                            </td>
                                            <td>
                                                @if ($cart->product != null && $cart->product->auction_product == 0)
                                                    <strong><a href="{{ route('product', $cart->product->slug) }}"
                                                            target="_blank"
                                                            class="text-muted">{{ $cart->product->getTranslation('name') }}</a></strong>
                                                    <small>{{ $cart->variation }}</small>
                                                @elseif ($cart->product != null && $cart->product->auction_product == 1)
                                                    <strong><a href="{{ route('auction-product', $cart->product->slug) }}"
                                                            target="_blank"
                                                            class="text-muted">{{ $cart->product->getTranslation('name') }}</a></strong>
                                                @else
                                                    <strong>{{ translate('Product Unavailable') }}</strong>
                                                @endif
                                            </td>
                                            <td>{{ $cart->created_at->format('d-m-Y h:i:s A') }}</td>
                                            <td class="text-center">{{ $cart->quantity }}</td>
                                            <td class="text-center">
                                                {{ single_price($cart->price) }}</td>
                                            <td class="text-center">{{ single_price($cart->price * $cart->quantity) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Grand Total</b></td>
                                        <td><b>{{ single_price($total_price) }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
