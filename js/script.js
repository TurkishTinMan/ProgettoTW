function reset(){
    $("#docClick").html("Documento caricato");
    $("#doc").html("<h3>Documento</h3><p>----</p>");
    $("#ul-metaarea-documents").html("-");
}

$( document ).ready(function(){
    reset();
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
            if(k=="Role"){
                $("#eventRole").html(v);
            }else{
                result=result+"<li><a onclick='LoadDocument(\""+k+"\")'>"+v+"</a></li>";
            }
        });
        $("#DocAreaBody").html(result);
      },
      error: function() {
        $("#DocAreaBody").html("Error!");
      }
    });  
}

function loaderMetaEventArea(numberEvent){
    if(numberEvent < 0){
        $("#ul-metaarea-events").html("-");
    }else{
        $.ajax({
          url: "./PHP/loaderMetaEventArea.php",
          type: "POST",
          data: {numberEvent : numberEvent},
          dataType: 'json',
          success: function(json_data){
            result = "";
            $.each(json_data,function(k,v){
                if(k != "submissions"){
                    result=result+"<li>"+k+":"+v+"</li>";
                }
            });
            $("#ul-metaarea-events").html(result);
          },
          error: function() {
            $("#ul-metaarea-events").html("-");
          }
        });  
    }
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


function LoadDocument(urlDocument) {  
    $.ajax({ 
        url:"./PHP/loaderDocument.php",
        type:"POST",
        data: {localUrl : urlDocument},
        dataType:'json',
        success: function(paper_json) {
            $("#docClick").html(paper_json["title"]);
            paper = "<h1>" + paper_json["title"] + "</h1><div>" + paper_json["body"] + "</div>";
            $("#doc").html(paper);
            
            startmetadati = "<li>";
            endmetadati="</li>";
            metadati = startmetadati + "keywords :";

            $.each(paper_json["keyword"],function(k,v){
                metadati = metadati + v + "<br>";
            });
            
            metadati = metadati + endmetadati;
            
            metadati = metadati + startmetadati + "Autori: "
            
            $.each(paper_json["Autori"],function(k,v){
                if(v["linked"] == "false"){
                    metadati = metadati + startmetadati;
                    metadati = metadati + v["name"] +v["email"] +v["affiliation"];
                    metadati = metadati + endmetadati;
                }
            });
            metadati = metadati + endmetadati;
            $("#ul-metaarea-documents").html(metadati);
            
        },
        error:function(){
            console.log('Error!');
        }
    });
    
    $("#docClick").click();
    
}

function ShowHideArea(idshow){
    if($(idshow).is(":visible")){
        $(idshow).hide();
    }else{
        $(idshow).show();
    }
}

function ChangeEvent(json_data_event){
    reset();
    loaderDocArea(json_data_event);
    loaderMetaEventArea(json_data_event);
}


function ChangePage(idpagebutton){
    $( document ).ready(function(){
        $(idpagebutton).click();
    });
}

function Notify(type,text){
    $( document ).ready(function(){
        output = "<div class='alert alert-";
        switch(type){
            case 'error':
                output = output + "danger";
            break;
            case 'success' :
                output = output + "success";
            break;
        }
        output = output + "'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+ text +"</div>";
        $('#notification').append(output);
    });
}