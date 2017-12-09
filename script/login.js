$(document).ready(init);

function init(){
    $("#search-button").on("click",login);
    $("#password-input").on("keydown",function(event){maybe_login(event);});
}

function maybe_login(event){
    if (event.keyCode == 13) //ENTER KEY
        login();
}

function login() {
        $.ajax({
        method: "POST",
<<<<<<< HEAD
        url: "server/login_session.php",
=======
        url: "LOGIN-PAGE",
>>>>>>> 6cad9dfeed89c64b04ac3b2bb2c3e0f99086db3b
        dataType: "text",
        data: new FormData($("#login_form")[0]),
        processData: false,
        contentType: false,
        success: function (data) {
<<<<<<< HEAD
        if($.trim(data)=="success"){
            window.location.assign("cars.html"); //redirect the page to cars.html
        }
        else{
            $("#loading").attr("class","loading_hidden"); //hide the loading icon
            $("#login_feedback").html($.trim(data)); //show feedback
=======
        if($.trim(data)=="success")
            window.location.assign("cars.html"); //redirect the page to cars.html
        else{
            $("#loading").attr("class","loading_hidden"); //hide the loading icon
            $("#login_feedback").html("Invalid username or password"); //show feedback
>>>>>>> 6cad9dfeed89c64b04ac3b2bb2c3e0f99086db3b
        }
        }
    });
}








