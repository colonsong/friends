@extends('admin.master')

@section('content')

    <div class="container-fluid">
        <h1 class="page-title">
            <i class=""></i> 
        </h1>
        <p>
        <div>
            <a href="" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
    
    
           
                <a href="" class="btn btn-primary btn-add-new">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>

        </div>

        <p>
                <table class="table table-dark">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">暱稱</th>
                        <th scope="col">年齡</th>
                        <th scope="col">性別</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($profiles as $profile)
                        
                        
                        <tr>
                            <th scope="row">{{ $profile->id}}</th>
                            <td>{{ $profile->name}}</td>
                            <td>{{ $profile->age}} 歲</td>
                            <td>{{ @$profile->gender == 1 ? '男' : '女'}}</td>
                            <td>
                            <a href="{{ url('admin/profiles/edit') }}" class="btn btn-info" role="button">Link Button</a>
                            <button type="button" class="btn btn-secondary">Secondary</button>
                            <button type="button" class="btn btn-success">Success</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        
    

    </div>




@stop