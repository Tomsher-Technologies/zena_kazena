@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Add New Shop') }}</h5>
    </div>

    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Shop Information') }}</h5>
            </div>
            <div class="card-body">
                <form id="formMap" action="{{ route('admin.shops.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('Name In English') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name In English') }}" id="name" name="name"
                                class="form-control" required value="{{ old('name') }}">
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('Name In Arabic') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name In Arabic') }}" id="name_ar" name="name_ar"
                                class="form-control" required  value="{{ old('name_ar') }}"> 
                            @error('name_ar')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{ translate('Phone') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Phone') }}" id="phone" name="phone"  value="{{ old('phone') }}"
                                class="form-control" required>
                            @error('phone')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{ translate('Email Address') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Email Address') }}" id="email"  value="{{ old('email') }}"
                                name="email" class="form-control" required>
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="password">{{ translate('Password') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="password" placeholder="{{ translate('Password') }}" id="password" name="password" class="form-control" autocomplete="new-password">
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{ translate('Address In English') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="address" id="us7-address" cols="30" rows="3"
                                placeholder="{{ translate('Address In English') }}" required> {{ old('address') }}</textarea>
                            @error('address')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{ translate('Address In Arabic') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="address_ar" id="us7-address" cols="30" rows="3"
                                placeholder="{{ translate('Address In Arabic') }}" required>{{ old('address_ar') }}</textarea>
                            @error('address_ar')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{ translate('Location') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {{-- <textarea class="form-control" name="address" id="us7-address" cols="30" rows="3"
                                placeholder="{{ translate('Address') }}" required></textarea> --}}
                            <input type="text" class="form-control" id="us3-address" />

                            {{-- <input type="text" id="us7-address" name="email" class="form-control"> --}}
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div id="us3" style="height: 400px;"></div>
                        </div>
                    </div>

                    <input type="hidden" name="lat" class="form-control" id="us3-lat" />
                    <input type="hidden" name="long" class="form-control" id="us3-lon" />
                   <input type="number" name="long" class="form-control" id="us3-radius" />

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places&v=weekly"></script>
    <script src="https://rawgit.com/Logicify/jquery-locationpicker-plugin/master/dist/locationpicker.jquery.js"></script>
    <script>
        function showPosition(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            loadMap(lat, lng)
        }

        function showPositionerror() {
            loadMap(25.2048, 55.2708)
        }

        function loadMap(lat, lng) {
            $('#us3').locationpicker({
                location: {
                    latitude: lat,
                    longitude: lng
                },
                radius: 0,
                inputBinding: {
                    latitudeInput: $('#us3-lat'),
                    longitudeInput: $('#us3-lon'),
                    radiusInput: $('#us3-radius'),
                    locationNameInput: $('#us3-address')
                },
                enableAutocomplete: true,
                onchanged: function(currentLocation, radius, isMarkerDropped) {
                    // Uncomment line below to show alert on each Location Changed event
                    //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                }
            });
        }

        $(document).ready(function() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(showPosition, showPositionerror);
            } else {
                console.log("asas");
                loadMap(25.2048, 55.2708)
            }
        });
    </script>
@endsection
