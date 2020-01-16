@extends("base",['title'=>'網誌文章'])

@section('title', '網誌文章')


@section('body')
    <div class="container">
        <a href="{{route('profiles.create')}}"><input class="btn btn-primary" name="" type="button" value="上傳簡介"/></a>
        <ul>
        @foreach ($profiles as $profile)
            <li>
                
                <a href="{{route('profiles.show',[$profile->id])}}">{{ $profile->name }}{{ $profile->age }}
                    {{ $profile->gender }}</a>
                <form class="form-inline"
                      action="{{route('profiles.destroy',[$profile->id])}}"
                      method="post">
                    @csrf
                    @method('delete')
                    <input class="btn btn-danger" name="" type="submit" value="刪除"/>
                </form>
            </li>
        @endforeach
        </ul>
    </div>

    {{ $profiles->links() }}
@endsection