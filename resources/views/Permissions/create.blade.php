@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create a Permissions') }}
    </h2>
@endsection
@extends('layouts.templatecss')
@section('content')
    <!-- START FORM -->
    <a href="{{ url('permissions') }}" class="my-3 btn btn-secondary"><< Back</a>
    <div class="col-lg-8">
        <form action='{{ url('permissions') }}' method='post'>
            @csrf
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="mb-3 row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='name' id="name">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="save" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">SAVE</button>
                    </div>
                </div>
        </form>
    </div>
    <!-- AKHIR FORM -->
@endsection
