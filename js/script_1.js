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
        dataType:'json',
        success: function(doc_data){
        	
            risult = "<div>" + doc_data + "</div>";
            
            $("#doc").html(risult);
            
            
        },
        error:function(){
            console.log("ERROR");
        }
    });
}
