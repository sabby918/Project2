$(document).ready(init);

function init() {
    $("#find-car").on("click",find_car);
    $("#returnedcars").on("click",returned_cars);
    $("#2").on("click",rent_car);
    $("#password-input").on("keydown",function(event){maybe_login(event);});
    //show_rentals();
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


    
function returned_cars(){
    $.ajax({
        method: "POST",
        url:"server/returncontroller.php",
        dataType:"json",
        data:{returned_cars:$("#returned_cars").val()},
        success: function (data){
            var info_template=$("#returned-car-template").html();
            var html_maker= new htmlMaker(info_template);
            var html = html_maker.getHTML(data);
            $("#returned_cars").html(html);
        }
    });

}

