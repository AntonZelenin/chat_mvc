<!DOCTYPE html>
<html>
    <head>
        <title>Home | @chat :)</title>
        <script src="\app\js\jquery-3.2.1.js"></script>
        <link rel="stylesheet"  href="\public\css\login.css" />

    </head>

    <body>

        <div class='form-div-container'>
            <form class="form-container" name="authorization">
                <div class="form-row">
                    <input class="form-input" type="text" id='login' placeholder="Login" />
                </div>

                <div class="form-row">
                    <input class="form-input" type="password" id='password' placeholder="Password" />
                </div>

                <div class="form-row">
                    <input class="submit-button" type="button" value="Log in" onclick="auth()"/>
                    <a class="forgot-password" href="http://www.google.com">Forgot your password?</a>
                </div>

            </form>


            <div class="form-container" name="registration">
                <div class="form-row">
                    For the first time on @chat :)?
                </div>

                <div class="form-row">
                    <input class="form-input" type="text" id="reg-login" placeholder="Create login" />
                </div>

                <div class="form-row">
                    <input class="form-input" type="text" id="reg-username" placeholder="Username" />
                </div>

                <div class="form-row">
                    <input class="form-input" type="password" id="reg-password" placeholder="Come up with a password" />
                </div>

                <div class="form-row">
                    <input class="form-input" type="password" id="confirm-password" placeholder="Confirm password" />
                </div>

                <div class="form-row">
                    <input class="submit-button" type="submit" value="Sign up" onclick="reg()" />
                </div>

            </div>


        </div>

        <div class="warning">
            <div id="big-warning">Welcome!</div>
            <div id="small-warning">To... here.<br />VK sucks, I'm doing well:)</div>
        </div>


        <form "reg-form-container">

        </form>

    </body>

</html>

<script>

    function auth(){
        login = document.getElementById('login').value;
        password = document.getElementById('password').value;

        $.post(
            '\\public\\login',
            {
                login: login,
                password: password
            },
            function(respond){
                if(respond == true){
                    window.location = "home.php";
                }else{
                    set_warnings('Fuck!', 'You entered something wrong.<br />Try again');
                }
            }
        );
    }

    function set_warnings(big, small){
        var div = document.getElementById('big-warning');
        div.innerHTML = big;

        div = document.getElementById('small-warning');
        div.innerHTML = small;
    }

    function reg(){
        reg_login = document.getElementById('reg-login').value;
        reg_username = document.getElementById('reg-username').value;
        reg_password = document.getElementById('reg-password').value;
        confirm_password = document.getElementById('confirm-password').value;

        if(reg_login == '' && reg_password == '' && confirm_password == ''){
            set_warnings('Empty!!', 'You didn\'t fill anything, kidding?');

            return;
        }

        if(reg_login == ''){
            set_warnings('It\'s empty!<br />', 'You didn\'t even fill login field:( Fill it');

            return;
        }else if (reg_username == '') {
            set_warnings('Username<br />', 'Where is it?');

            var div = document.getElementById('big-warning');

            return;
        }else if (reg_password == '') {
            set_warnings('Password<br />', 'How\'re you going to sign in without the password?');

            var div = document.getElementById('big-warning');

            return;
        }else if (confirm_password == '') {
            set_warnings('Confirm!', 'You must enter password twice! What if you misstyped?');

            return;
        }

        if(reg_password != confirm_password){
            set_warnings('Typo', 'You entered some abracadabra. Passwords do not match');

            return;
        }

        $.post(
            '\\public\\login',
            {
                reg_login: reg_login,
                reg_username: reg_username,
                reg_password: reg_password
            },

            function(respond){
                if(respond == true){
                    set_warnings('Great!', 'You\'ve just registereg) Enjoy!');

                    setTimeout(function(){ window.location = "/tpl/home.php"; }, 2500);
                }else{
                    set_warnings('Oops!', 'Sorry, login is already occupied. Pick another one');
                }
            }
        );
    }


</script>
