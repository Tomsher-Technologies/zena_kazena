@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                @endphp

                <!--Assign Delivery Boy-->
                @if (addon_is_activated('delivery_boy'))
                    <div class="col-md-3 ml-auto">
                        <label for="assign_deliver_boy">{{ translate('Assign Deliver Boy') }}</label>
                        @if ($delivery_status == 'pending' || $delivery_status == 'confirmed' || $delivery_status == 'picked_up')
                            <select class="form-control aiz-selectpicker" data-live-search="true"
                                data-minimum-results-for-search="Infinity" id="assign_deliver_boy">
                                <option value="">{{ translate('Select Delivery Boy') }}</option>
                                @foreach ($delivery_boys as $delivery_boy)
                                    <option value="{{ $delivery_boy->id }}"
                                        @if ($order->assign_delivery_boy == $delivery_boy->id) selected @endif>
                                        {{ $delivery_boy->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ optional($order->delivery_boy)->name }}"
                                disabled>
                        @endif
                    </div>
                @endif

                <div class="col-md-3 ml-auto">
                    <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                    <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                        id="update_payment_status">
                        <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>{{ translate('Unpaid') }}
                        </option>
                        <option value="paid" @if ($payment_status == 'paid') selected @endif>{{ translate('Paid') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-3 ml-auto">
                    <label for="update_delivery_status">{{ translate('Delivery Status') }}</label>
                    @if ($delivery_status != 'delivered' && $delivery_status != 'cancelled')
                        <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                            id="update_delivery_status">
                            <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                {{ translate('Pending') }}</option>
                            <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                {{ translate('Confirmed') }}</option>
                            <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                {{ translate('Picked Up') }}</option>
                            <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                {{ translate('On The Way') }}</option>
                            <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                {{ translate('Delivered') }}</option>
                            <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                {{ translate('Cancel') }}</option>
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                    @endif
                </div>
                <div class="col-md-3 ml-auto">
                    <label for="update_tracking_code">{{ translate('Tracking Code (optional)') }}</label>
                    <input type="text" class="form-control" id="update_tracking_code"
                        value="{{ $order->tracking_code }}">
                </div>
            </div>
            <div class="mb-3">
                @php
                    $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                @endphp
                {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!}
            </div>
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                    <address>
                        <strong class="text-main">{{ json_decode($order->shipping_address)->name }}</strong><br>
                        {{ json_decode($order->shipping_address)->email }}<br>
                        {{ json_decode($order->shipping_address)->phone }}<br>
                        {{ json_decode($order->shipping_address)->address }},
                        {{ json_decode($order->shipping_address)->city }},
                        {{ json_decode($order->shipping_address)->postal_code }}<br>
                        {{ json_decode($order->shipping_address)->country }}
                    </address>
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}: {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}"
                            target="_blank"><img
                                src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100"></a>
                    @endif
                </div>
                <div class="col-md-3 ml-auto">
                    <table class="w-100">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-right text-info text-bold"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span
                                            class="badge badge-inline badge-success">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @else
                                        <span
                                            class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }} </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    {{ translate('Total amount') }}
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-bordered aiz-table invoice-summary">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th class="text-uppercase">{{ translate('Description') }}</th>
                                <th data-breakpoints="lg" class="text-uppercase">{{ translate('Delivery Type') }}</th>
                                <th data-breakpoints="lg" class="min-col text-center text-uppercase">{{ translate('Qty') }} </th>
                                <th data-breakpoints="lg" class="min-col text-center text-uppercase"> {{ translate('Price') }}</th>
                                <th data-breakpoints="lg" class="min-col text-center text-uppercase"> {{ translate('Total') }}</th>
                                <th data-breakpoints="lg" class="min-col text-center text-uppercase"> {{ translate('Trasfer') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                           
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                target="_blank"><img height="50"
                                                    src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                target="_blank"><img height="50"
                                                    src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                        @else
                                            <strong>{{ translate('N/A') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <strong><a href="{{ route('product', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                            <small>{{ $orderDetail->variation }}</small>
                                            <span class="d-block">SKU: {{ $orderDetail->product->sku }}</span>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <strong><a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                                    <span class="d-block">SKU: {{ $orderDetail->product->sku }}</span>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->getTranslation('name') }}
                                                ({{ translate('Pickup Point') }})
                                            @else
                                                {{ translate('Pickup Point') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        @if($orderDetail->original_price != '')
                                            <span class="text-muted text-decoration-line-through">
                                                {{ single_price($orderDetail->original_price) }}
                                            </span>
                                        @endif
                                        <br>
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                    </td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                                    <td class="text-center">
                                        @if($orderDetail->order_transfer()->exists())
                                            @if($orderDetail->order_transfer->status == 0)
                                                <span class="badge bg-soft-danger w-50 h-100 fs-13"> Transferred </span>
                                            @elseif($orderDetail->order_transfer->status == 1)
                                                <span class="badge bg-soft-success w-50 h-100 fs-13"> Received </span>
                                            @endif
                                        @else
                                            <a href="#" class="btn btn-info" onclick="transferProduct({{$orderDetail}})"> Transfer </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Tax') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Shipping') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right no-print">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button"
                        class="btn btn-icon btn-light"><i class="las la-print"></i></a>
                </div>
            </div>


            <div class="modal fade" id="show_transfer_popup" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="" id="heading-data">{{ translate('Transfer Product') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="overflow: auto;">
                            <div class="row">
                                <div class="col-12">
                                    <form id="transferForm" action="{{ route('transfer-product') }}" name="transferForm" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">{{ translate('Select Shop') }}<span class="text-danger">*</span></label>
                                                    <select class="form-control select2" name="store_id" id="store_id" required>
                                                        <option value="">{{ translate('Select Shop') }}</option>
                                                        @foreach ($shops as $shop)
                                                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="order_id" id="order_id" value="">
                                                    <input type="hidden" name="shop_from_id" id="shop_from_id" value="{{$shop_id}}">
                                                    <input type="hidden" name="product_id" id="product_id" value="">
                                                    <input type="hidden" name="quantity" id="quantity" value="">
                                                    <input type="hidden" name="order_detail_id" id="order_detail_id" value="">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group text-center">
                                                    <button type="submit" class="btn btn-sm btn-primary">{{translate('Transfer')}}</button>
                                                </div>
                                            </div>

                                        </div>              
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#assign_deliver_boy').on('change', function() {
            var order_id = {{ $order->id }};
            var delivery_boy = $('#assign_deliver_boy').val();
            $.post('{{ route('orders.delivery-boy-assign') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                delivery_boy: delivery_boy
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery boy has been assigned') }}');
            });
        });

        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
            });
        });

        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order tracking code has been updated') }}');
            });
        });
        function transferProduct(data){
            console.log(data);
            $('#order_id').val(data.order_id);
            $('#product_id').val(data.product_id);
            $('#quantity').val(data.quantity);
            $('#order_detail_id').val(data.id);
            $('#show_transfer_popup').modal('show');
        }
    </script>
@endsection
