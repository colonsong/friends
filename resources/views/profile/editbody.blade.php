
    <form method="post" action="{{
        ($type=="edit") ? route("profiles.update", [$id]) : route("profiles.store")
    }}" method="post" enctype="multipart/form-data">

        @csrf
        @method(($type=="edit") ? "patch" : "post")
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <input name="file" type="file" accept="image/*" value=""/>

        <input name="name" type="text" value="{{$name}}" id="name"/>
        <br/>

        <input name="age" type="text" value="{{$age}}" id="age"/>
        <br/>

        <input name="gender" type="text" value="{{$gender}}" id="gender"/>
        <br/>

        <input name="" type="submit" value="儲存"/>

    </form>

