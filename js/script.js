$( document ).ready(function() {
    $.ajax({
      url: "./PHP/loaderDocArea.php",
      type: "GET",
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
}); 

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