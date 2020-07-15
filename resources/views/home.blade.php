@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!

                    <a class="text-muted" href="{{url('admin')}}">管理員</a> ,
                    <a class="text-muted" href="{{url('profiles')}}">前台</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
