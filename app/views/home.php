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

            <input type="text" id="msg" />
            <input type="button" onclick="send()" />
        </div>

    </div>


        <script>

            function foo()
            {

            }

            var wsUri = "ws://chat.php:9000";
            websocket = new WebSocket(wsUri);

            websocket.onerror = function(ev) {
                $('#msg').val($('#msg').val() + "Error Occurred - " + ev.data + "\r\n");
            }

            websocket.onclose = function(ev) {
                $('#msg').val($('#msg').val() + "Connection closed" + "\r\n");
            }

            websocket.onopen = function(ev) {
                $('#msg').val($('#msg').val() + "Connected!" + "\r\n");
            }

            websocket.onmessage = function(ev) {
                // alert(1);
                var msg = JSON.parse(ev.data);
                console.log(msg);
                var type = msg.type;
                var umsg = msg.message;
                var uname = msg.name;
                var ucolor = msg.color;
                if(type == 'usermsg')
                {
                    $('#message_box').append("<div><span class=\"user_name\" style=\"color:#"+ucolor+"\">"+uname+"</span> : <span class=\"user_message\">"+umsg+"</span></div>");
                }
                if(type == 'system')
                {
                    $('#msg').val($('#msg').val() + umsg + "\r\n");
                }

                // var objDiv = document.getElementById("message_box");
                // objDiv.scrollTop = objDiv.scrollHeight;
            };

            function choose_dialog(){

            }

            function send() {
            	var mymessage = $("#msg").text;

            	if(!mymessage) {
            		alert("Message can not be empty");
            		return;
            	}

            	$("#msg").text="";
            	$("#msg").focus();

                msg = {
                    message: mymessage,
                    recipient_id: 4
                };

            	try {
            		websocket.send(JSON.stringify(msg));
            		log('Sent: ' + msg);
            	} catch(ex) {
            		log(ex);
            	}
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

        <b>WebSocket v2.00</b>

        </body>
</html>
