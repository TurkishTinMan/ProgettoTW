$( document ).ready(function(){
    loaderDocArea("-1");
    loaderEventArea();
});

function loaderDocArea(numberEvent) {
    $.ajax({
      url: "./PHP/loaderDocArea.php",
      type: "POST",
      data: {numberEvent : numberEvent},
      dataType: 'json',
      success: function(json_data){
        result = "";
        $.each(json_data,function(k,v){
            result=result+"<li><a onclick='LoadDocument(\""+k+"\")'>"+v+"</a></li>";
        });
        $("#DocAreaBody").html(result);
      },
      error: function() {
        $("#DocAreaBody").html("Error!");
      }
    });  
}

function loaderEventArea() {
    $.ajax({
      url: "./PHP/loaderEventArea.php",
      type: "GET",
      dataType: 'json',
      success: function(json_data){
        result = "";
        for(var i=0; i<=json_data.length;i++){
            $.each(json_data[i],function(k,v){
                if(k=="conference"){
                    result=result+"<li><a onclick='ChangeEvent(\""+i+"\")'>"+v+"</a></li>";
                }
            });
        }
        result = result+"<li><a onclick='ChangeEvent(\"-1\")'> All Event </a></li>"
        $("#EventAreaBody").html(result);
      },
      error: function() {
        $("#EventAreaBody").html("Error!");
      }
    });  
}


function LoadDocument(urlDocument){   
    $.ajax({
        url:"./PHP/loaderDocument.php",
        type:"POST",
        data: {localUrl : urlDocument},
        dataType:'html',
        success: function(html_data){
            $("#doc").html(html_data);
        },
        error:function(){
            console.log("errore");
        }
    });
}

function ShowHideArea(idshow){
    if($(idshow).is(":visible")){
        $(idshow).hide();
    }else{
        $(idshow).show();
    }
}

function ChangeEvent(json_data_event){
    loaderDocArea(json_data_event);
}