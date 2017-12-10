$(document).ready(init);

function init() {
    $("#find-car").on("click",find_car);
    $("#logout-link").on("click",logout);
    $('#findcar').keypress(function(event) {
        if (event.keyCode == 13 || event.which == 13)
            find_car();
        });
    show_rentals();
    returned_cars();
}


function maybe_search(event){
    //if(event.keycode == 13)
        find_car();
}

function find_car(){
    var attribute = $("#findcar").val();
    $.ajax({
        method: "POST",
        url: "server/controller.php",
        dataType: "json",
        data: {type: "find", attribute: attribute},
        success: function (data) {
            var info_template=$("#find-car-template").html();
            var html_maker=new htmlMaker(info_template);
            var html=html_maker.getHTML(data);
            $("#search_results").html(html);
            attach_events();
        }
    });    
}

function show_rentals(){
    $.ajax({
        method: "POST",
        url: "server/controller.php",
        dataType: "json",
        data: { type: "rentals"},
        success: function (data) {
            //var info_template=$('#rented-car-template').html();
            var html_maker=new htmlMaker($('#rented-car-template').html());
            var html=html_maker.getHTML(data);
            $("#rented_cars").html(html);
            attach_events();
        }
    });    
}

function returned_cars(){
    $.ajax({
        method: "POST",
        url:"server/controller.php",
        dataType:"json",
        data: {type: "returned"},
        success: function (data){
            var info_template=$("#returned-car-template").html();
            var html_maker= new htmlMaker(info_template);
            var html = html_maker.getHTML(data);
            $("#returned_cars").html(html);
        }
    });

}

function logout() {
    $.ajax({
        method: "POST",
        url: "server/controller.php",
        dataType: "text",
        data: {type: "logout"},
        success: function (data) {
            if ($.trim(data)=="success") {
                window.location.assign("index.html");
            }
        }
    });
}

function rent_car(id){
    $.ajax({
        method: "POST",
        url: "server/controller.php",
        dataType: "text",
        data: {type: "rent", id:id},
        success: function (data){
            if($.trim(data)=="success"){
                find_car();
                show_rentals();
                alert("The car has been rented successfully");
            }
            else{
                alert("could not rent car");
            }
        }
    });
}

function return_car(id){
    $.ajax({
        method: "POST",
        url: "server/controller.php",
        dataType: "text",
        data: {type: "return", id:id},
        success: function (data){
            if($.trim(data)=="success"){
                find_car();
                show_rentals();
                returned_cars();
                alert("The car has been returned successfully");
            }
            else{
                alert("could not return car");
            }
        }
    });
}

function add_record(id){
    $.ajax({
        method: "POST",
        url: "server/controller.php",
        dataType: "text",
        data: {type: "add", id:id},
        success: function (data){
            if($.trim(data)=="success"){
                rent_car(id);
            }
            else{
                alert("Could not create record");
            }
        }
    });
}

function update_record(id){
    $.ajax({
        method: "POST",
        url: "server/controller.php",
        dataType: "text",
        data: {type: "update", id:id},
        success: function (data){
            if($.trim(data)=="success"){
                return_car(id);
            }
            else{
                alert("Could not update record");
            }
        }
    });
}

function attach_events(){
    $("div[class=car_rent]").on("click",function(){
        var item_id=$(this).attr("id");
        var value=$(this).val();
        add_record(item_id);
    });
    
    $("div[class=return_car]").on("click",function(){
        var item_id=$(this).attr("data-rental-id");
        var value=$(this).val();
        update_record(item_id);
    });
      
}
