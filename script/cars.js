$(document).ready(init);

function init() {
<<<<<<< HEAD
    $("#find-car").on("click",find_car);
    $("#2").on("click",rent_car);
    $("#password-input").on("keydown",function(event){maybe_login(event);});
    show_rentals();
}

function find_car(){
   
    $.ajax({
        method: "POST",
        url: "server/findcontroller.php",
        dataType: "json",
        data: {findcar: $("#findcar").val()},
        success: function (data) {
            var info_template=$("#find-car-template").html();
            var html_maker=new htmlMaker(info_template);
            var html=html_maker.getHTML(data);
            $("#search_results").html(html);
        }
    });    
}

function rent_car(){
    $.ajax({
        method: "POST",
        url: "server/login_session.php",
        dataType: "text",
        data: new FormData($("#login_form")[0]),
        processData: false,
        contentType: false,
        success: function (data) {
        if($.trim(data)=="success"){
            window.location.assign("cars.html"); //redirect the page to cars.html
        }
        else{
            $("#loading").attr("class","loading_hidden"); //hide the loading icon
            $("#login_feedback").html("Invalid username or password"); //show feedback
        }
        }
    });
}

function show_rentals(){
    $.ajax({
        method: "POST",
        url: "server/rentedcontroller.php",
        dataType: "json",
        data: { type: "rented_cars"},
        success: function (data) {
            var info_template=$("#rented-car-template").html();
            var html_maker=new htmlMaker(info_template);
            var html=html_maker.getHTML(data);
            $("#rented_cars").html(html);
        }
    });    
}
=======

}
>>>>>>> 6cad9dfeed89c64b04ac3b2bb2c3e0f99086db3b
