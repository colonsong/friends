@extends("base", ['title'   => '編輯文章'])

@section('title', '編輯文章2')


@section('body')

    <form method="post" action="{{
        ($type=="edit") ? route("profiles.update", [$id]) : route("profiles.store")
    }}">

        @csrf 
        @method(($type=="edit") ? "patch" : "post")

        

        <input name="name" type="text" value="{{$name}}" id="name"/>
        <br/>

        <input name="age" type="text" value="{{$age}}" id="age"/>
        <br/>

        <input name="gender" type="text" value="{{$gender}}" id="gender"/>
        <br/>
  


        <input name="" type="submit" value="儲存"/>

    </form>


@endsection