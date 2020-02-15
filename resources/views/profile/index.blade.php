@extends("base",['title'=>'網誌文章'])

@section('title', '網誌文章')


@section('body')
{{--
    <div class="container">
        <a href="{{route('profiles.create')}}"><input class="btn btn-primary" name="" type="button" value="上傳簡介"/></a>
        <ul>



       
        @foreach ($profiles as $profile)
           

                <div class="card" style="width: 18rem;">
                @if (!empty($profile->images()->first()->images_path))
                    <img src="{{Storage::url($profile->images()->first()->images_path)}}" class="card-img-top" alt="...">
                @endif
                    <div class="card-body">
                        <p class="card-text"><a href="{{route('profiles.show',[$profile->id])}}">{{ $profile->name }}{{ $profile->age }}
                            {{ $profile->gender }}</a>
                        </p>
                    </div>
                </div>  
            
        @endforeach

       
        </ul>
    </div>

    {{ $profiles->links() }}
    --}}
    <div id="app">
            <example-component></example-component>
    </div>
 


@endsection


