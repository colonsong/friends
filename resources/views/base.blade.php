<!doctype html>
<html lang="zh-Hant-TW">

<head>
    <meta charset="UTF-8" />
    <title>@yield("titile", "aaaa Example Blog of Laravel Tutorial")</title>

    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">


</head>

<body>



    <div class="container">
        <header class="blog-header py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 pt-1">
                    <a class="text-muted" href="{{url('admin')}}">管理員</a>
                </div>
                <div class="col-4 text-center">
                    <a class="blog-header-logo text-dark" href="#">交個朋友吧</a>
                </div>
                <div class="col-4 d-flex justify-content-end align-items-center">
                    <a class="text-muted" href="#" aria-label="Search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img"
                            viewBox="0 0 24 24" focusable="false">
                            <title>Search</title>
                            <circle cx="10.5" cy="10.5" r="7.5" />
                            <path d="M21 21l-5.2-5.2" />
                        </svg>
                    </a>
                    <a class="btn btn-sm btn-outline-secondary" href="{{URL::route('login')}}">登入</a>
                </div>
            </div>
        </header>


        <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
            <form method="POST" action="logout.php" id="outAccount" name="outAccount">
                <a class="btn btn-default btn-top " data-toggle="modal" data-target="#exampleModal" role="button"
                    onClick="logoutBt()">登出</a>
            </form>


            <div class="container-fluid chat-content">
                <div class="row content">
                    <div class="col-sm-3 sidenav hidden-xs chat-bar">
                        <ul class="nav nav-pills nav-stacked" id="us" style="display:block">
                        </ul>
                    </div>
                    <div class="col-sm-9 well-border">
                        <div class="well well-bar" id="ct">
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group chat-typing">
                                    <textarea rows="3" id="nrong"></textarea>
                                    <button id="sd" type="button" class="btn btn-outline-primary">發送</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>

    <main role="main" class="container">



        @section('body')
        以下是亂文內容<br /><br /><br />
        @show




    </main><!-- /.container -->




    <footer class="blog-footer">
        <p>Blog template built for <a href="https://getbootstrap.com/">Bootstrap</a> by <a
                href="https://twitter.com/mdo">@mdo</a>.</p>
        <p>
            <a href="#">Back to top</a>
        </p>
    </footer>


    <script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>


    <script>
        function logoutBt(){
            document.getElementById('outAccount').submit();
        }
    </script>

    <script>
        $(document).ready(function() {
            console.log($);


            var key='all',mkey;
            var users={};
            var url='ws://localhost:3333/';

            var so=false,name='';
            var lus=$('#us');
            var lct=$('#ct');

            //一進頁面請他先取一個匿稱
            function socketStart(){

                if (!window.WebSocket) {
                    alert('not support socket');
                }
                name = prompt('請给自己取一個響亮的名字：');
                name = name.substr(0,16);
                if (!name) {
                    return ;
                }

                //創建socket，注意URL的格式：ws://ip:端口
                so = new WebSocket(url);

                console.log(so);
                //连接建立
                so.onopen = function(){
                // 狀態為1證明握手成功，然後把client自定義的名字發送過去
                    if(so.readyState == 1){
                        so.send('type=add&ming='+name);
                    }
                }

                //握手失敗或者其他原因連接socket失敗，則清除so對象並做相應提示操作
                so.onclose = function(){
                    so=false;
                    $('#ct').append('<div class="dialog-me" ><div class="dialog-content-me">退出聊天室</div></div>');
                }


                so.onerror = function (event) {

                    console.log("Connected to WebSocket server error");


                }

                // 收到消息
                so.onmessage = function(msg) {


                    var da= JSON.parse(event.data);
                    console.log('@');
                    var obj=false,c=false;
                    //da.code 發訊息人的code
                    //da.code1 收訊息人的code  這裡的範例是傳給全部人 ->> all
                    //下面是進入聊天室的歡迎訊息和新增使用者到使用者列表裡

                    if (da.type=='add') {
                        console.log('@');
                    // var obj=$('<li><a>'+da.name+'</a></li>');
                    // $('#us').append(obj);
                        //上面兩個新增節點的語句也可以換成下面兩句
                        //var ob2=$('<li><a>'+da.name+'</a></li>');
                        //document.getElementById('us_small').append(ob2);
                        cuser(obj,da.code);

                        c = da.code;




                        var obj = '';
                        for(var i=0;i<da.users.length;i++){
                            obj +='<li><a>'+da.users[i]+'</a></li>';
                        }

                        $('#us').html(obj);

                        obj = '<div><h5>歡迎' + da.name + '加入</h5><div class="dialog" >';


                    } else if (da.type=='ltiao') {
                    // 握手後的發言發言時
                        console.log('@');
                        mkey=da.code;
                        da.users.unshift({'code':'all','name':'ALL'});
                        var obj;
                        for(var i=0;i<da.users.length;i++){
                            obj='<li><a>'+da.users[i].name+'</a></li>';

                            if(mkey!=da.users[i].code){
                                cuser(obj,da.users[i].code);
                            }else{
                                obj.className='my';
                                document.title=da.users[i].name;
                            }
                        }

                        $('#us').html(obj);

                        obj="<h5>歡迎"+da.name+"加入</h5>";
                        users.all.className='ck';
                    }

                    if (obj==false) {
                        if(da.type=='rmove'){
                            //如果傳過來的動作是退出聊天室要把users裡面該名使用者的資訊去除掉
                            var obj=$("<h5>"+users[da.nrong].innerHTML+"退出聊天室</h5>");
                            $('#ct').append(obj);
                            users[da.nrong].del();
                            delete users[da.nrong];
                        } else {

                        console.log(da);

                        //這裡是發送訊息的動作的code

                            //自己說話
                        if (da.code==mkey) {
                            console.log(da);
                            obj= $('<div class="dialog-me" ><div class="dialog-content-me"><span class="timestamp-me" style="color:red;">時間:'+da.time+'NAME:'+da.name+':</span>'+da.nrong+'</div></div>');
                            c=da.code1;
                        } else if(da.code1) {
                            //別人說話
                            var temp = "<div><h5>"+users[da.code].innerHTML+"</h5><div class='dialog'><div class='dialog-content'>"+da.nrong+"</div><span class='timestamp'>"+da.time+"</span></div></div>";

                            obj=$(temp);
                            c=da.code;
                        }
                        }
                    }

                    if (c) {

                        obj.children[1].onclick = function() {
                            users[c].onclick();
                        }
                    }
                    //新增訊息
                    console.log(obj);
                $('#ct').append(obj);
                $('#ct').scrollTop=Math.max(0,$('#ct').scrollHeight-$('#ct').offsetHeight);

                }
            }

                $(document).on('click', '#sd', function() {

                    if(!so){
                        return socketStart();
                    }
                    var da = $('#nrong').val().trim();
                    console.log(da);
                    if (da =='') {
                        alert('内容不能為空');
                        return false;
                    }
                    $('#nrong').val('');
                    so.send('nr='+esc(da)+'&key='+key+'&type=ltiao');
                });

            $('#nrong').onkeydown = function(e){
                var e=e||event;
                if (e.keyCode==13) {
                    $('sd').onclick();
                }
            }
            function esc(da){
                da=da.replace(/</g,'<').replace(/>/g,'>').replace(/\"/g,'"');
                return encodeURIComponent(da);
            }
            function cuser(t,code){
                users[code]=t;
                t.onclick=function(){
                    t.parentNode.children.rcss('ck','');
                    t.rcss('','ck');
                    key=code;
                }
            }

            socketStart();

                });


    </script>

</body>

</html>
