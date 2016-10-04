$( document ).ready(function() {
    $.ajax({
      url: "./PHP/loaderDocArea.php",
      type: "GET",
      dataType: 'json',
      success: function(json_data){
        console.log(json_data);
        result = "";
        $.each(json_data,function(k,v){
            result=result+"<li><a href="+k+">"+v+"</a></li>";
        });
        $("#DocAreaBody").html(result);
      },
      error: function() {
        $("#DocAreaBody").html("Error!");
      }
    });  
}); 