function checkForm(){
    var password = document.getElementById('reg-password').value;
    var confirm_password = document.getElementById('confirm-password').value;

    if(password != confirm_password){
        //что-то сделать
        alert('Passwords do not match');

        return false;
    }

    // $.post(
    //     '\\public\\login\\register',
    //     {
    //         reg_login: reg_login,
    //         reg_username: reg_username,
    //         reg_password: reg_password
    //     },
    //     function(respond){
    //         if(respond == true){
    //             set_warnings('Great!', 'You\'ve just registereg) Enjoy!');
    //             setTimeout(function(){ window.location = "/public/home.php"; }, 2500);
    //         }else{
    //             set_warnings('Oops!', 'Sorry, login is already occupied. Pick another one');
    //         }
    //     }
    // );
}
