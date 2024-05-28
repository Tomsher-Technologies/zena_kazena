@extends('backend.layouts.app')

@section('content')

<div class="col-lg-10 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Update Role Information')}}</h5>
        </div>
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 col-from-label" for="name">{{translate('Role Name')}}</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="{{translate('Role Name')}}" value="{{ old('name', $role->name) }}" id="name" name="name" class="form-control">
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
               
                <div class="form-group row">
                    <label  class="col-md-3 col-from-label">Permissions</label>
                    <div class="col-md-9">
                        @foreach($permission as $value)
                            @php 
                                $selected = '';
                                if(in_array($value->id, old('permission', $rolePermissions))){
                                    $selected = 'checked';
                                }
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-from-label">{{  $value->title }}</label>
                                </div>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" id="permission" class="form-control demo-sw" value="{{$value->name}}" {{ $selected }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                {{-- <label class="fs-14 fw-400">
                                    <input class="custom-checkbox name" type="checkbox" value="{{$value->name}}" name="permission[]"  {{ $selected }}>
                                    {{ $value->title }}
                                </label> --}}
                            </div>
                        @endforeach
                        @error('permissions')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-cancel">Cancel</a>
                </div>
            </div>
        </from>
    </div>
</div>

@endsection
