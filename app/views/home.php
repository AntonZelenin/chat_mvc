<!DOCTYPE html>
<html>
    <head>
        <title>Home | @chat :)</title>
        <script src="\app\js\jquery-3.2.1.js"></script>
        <link rel="stylesheet"  href="\public\css\home.css" />

    </head>

    <body>

    <div class="main">

        <div class="header" id="header">
            <input type="button" id="logout" onclick="logout()" value="Log out" />
            <div class="home-link-div">
                <a class="home-link" href="#">@chat :)</a>
                <!-- href="http://chat.php/tpl/home.php" -->
            </div>

        </div>

        <div class="dialogs" id="dialogs">
            <div class="searchbox">
                <input id="searchbox" type="search" placeholder="search">
            </div>

            <div class="dialogs-list" id="dialogs-list">

                <!-- <div class="user" onclick="choose_dialog()">
                    <img src='/img/anonymus.png' class="img-circle"/>
                    <div class="user-name"> <b>Anton</b> </div>
                    <div class="message-short">Hey, bro! you're..</div>
                </div> -->
            </div>

        </div>

        <div class="chat" id="chat">
            <div class="chat-empty">
                Please select a chat to start messaging :)
            </div>
        </div>

    </div>


        <script>

            function choose_dialog(){
                alert(1);
            }

            $('#searchbox').on('input propertychange paste', function() {
                $.get(
                    '/scripts/search.php',
                    {
                        template: $(this).val()
                    },
                    function(data) {
                        $('#dialogs-list').empty();
                        data = JSON.parse(data);
                        for(var i = 0; i < data.length; i++){
                            $('#dialogs-list').append(data[i]);
                        }
                    }
                );
            });

            function add_user(img, name, message){
                user = "<div class='user' onclick='foo()'> <img src='" + img +  "' class='img-circle'/><div class='user-name'> <b>" + name + "</b></div> <div class='message-short'>" + message + "</div></div>";
            }

            function logout(){
                $.ajax({
                    url : '/public/home',
                    type: 'DELETE',
                });

            }

        </script>

        </body>
</html>
