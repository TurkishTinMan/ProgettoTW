function reset(){
    $("#docClick").html("Documento caricato");
    $("#doc").html("<h3>Documento</h3><p>----</p>");
    $("#ul-metaarea-documents").html("-");
}

$( document ).ready(function(){
    $("nav>div>div>ul>li").attr('unselectable', 'on').css('user-select', 'none').on('selectstart', false);
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
                result=result+"<li class='list-group-item'><a onclick='LoadDocument(\""+k+"\")'>"+v+"</a></li>";
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
                    result=result+"<li class='list-group-item'>"+k+":"+v+"</li>";
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

function LoadAnnotation(urlDocument){
    $.ajax({
        url:"./PHP/loadAnnotation.php",
        type:"POST",
        data:{localUrl : urlDocument},
        dataType:'json',
        success:function(paper_json){
            $.each(paper_json,function(k,v){
                var html = $(v["Path"]).html();
                html = html.substring(0, parseInt(v["OffsetFromStart"])) + "<span style='background-color:yellow;' data-toggle='tooltip' title='"+v["Annotation"]+"'>" + html.substring(parseInt(v["OffsetFromStart"]),parseInt(v["LenghtAnnotation"])+parseInt(v["OffsetFromStart"]))+"</span>"+html.substring( parseInt(v["OffsetFromStart"]) + parseInt(v["LenghtAnnotation"]));
                $(v["Path"]).html(html);
                $('[data-toggle="tooltip"]').tooltip();   
                
                
                
            });
        },
        error:function(jqXHR, status, errorThrown) {
            console.log(jqXHR.responseText);
            console.log(status);
            console.log(errorThrown);
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
            
            startmetadati = "<li class='list-group-item'>";
            endmetadati="</li>";
            metadati = startmetadati + "keywords : <ul class='list-group list-unstyled'>";
            console.log(metadati);
            $.each(paper_json["keyword"],function(k,v){
                metadati = metadati + startmetadati + v + endmetadati;
            });
            
            metadati = metadati + "</ul>"
            
            metadati = metadati + startmetadati + "Autori:<ul class='list-group list-unstyled'>";
            
            $.each(paper_json["Autori"],function(k,v){
                if(v["linked"] == "false"){
                    metadati = metadati + startmetadati;
                    metadati = metadati + "<a href =\""+v["email"]+"\">"+v["name"]+"</a><p>"+v["affiliation"]+"</p>";
                    metadati = metadati + endmetadati;
                }
            });
            metadati = metadati + "</ul>";
            metadati = metadati + endmetadati;
            $("#ul-metaarea-documents").html(metadati);
            $("#Doc").val(urlDocument);
            LoadAnnotation(urlDocument);
        },
        error:function(jqXHR, status, errorThrown) {
            console.log(jqXHR.responseText);
            console.log(status);
            console.log(errorThrown);
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


function AddAnnotation(){
    if(checklog){
        var selection = window.getSelection();
        if(selection.toString().length != 0){
            var selectedText = selection.toString();
            var range = selection.getRangeAt(0);
            var tempContainer = range.startContainer;
            var check = false;
            if(range.startContainer == range.endContainer){
                content = "";
                while(tempContainer.nodeName != "BODY"){
                    tempContainer = tempContainer.parentNode;
                    if(tempContainer.id == "doc"){
                        content = "div#doc" + content;
                        check = true;
                        break;
                    }
                    n = $(tempContainer).prevAll(""+tempContainer.tagName).length;
                    content = ":eq("+n+")" + content;
                    content = tempContainer.tagName.toLowerCase() + content;                    
                    content = ">" + content;
                }
            }
            if(check){
                $("#Annotation-content").val(selectedText);
                $("#Path").val(content);
                $("#OffsetFromStart").val(selection.baseOffset);
                $("#LenghtAnnotation").val(selection.toString().length);
                $("#Data").val(Date.now());
                $("#AnnotationModal").modal({
                    show: 'true'
                });
            }else{
                Notify("error", "Devi selezionare qualcosa all'interno di un documento caricato per creare un'annotazione");
            }
        }else{
            Notify("error", "Devi selezionare qualcosa per creare un'annotazione");
        }
    }else{
        Notify("error", "Devi essere un Annotator per creare annotazioni");
    }
}

