@extends("base",['title'=>'網誌文章'])

@section('title', '網誌文章')


@section('body')
    <div class="container">
        <a href="{{route('profiles.create')}}"><input class="btn btn-primary" name="" type="button" value="上傳簡介"/></a>
        <ul>
        @foreach ($profiles as $profile)
           
                
                


                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text"><a href="{{route('profiles.show',[$profile->id])}}">{{ $profile->name }}{{ $profile->age }}
                            {{ $profile->gender }}</a></p>

                 
                        
                    </div>
                </div>


                
            
        @endforeach
        </ul>
    </div>

    {{ $profiles->links() }}
@endsection