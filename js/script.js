var urlCurrentDoc = "";

function randomCSS(){
    return "background-color:#"+Math.floor(Math.random()*16777215).toString(16);
}


function reset(){
    $("#docClick").html("Documento caricato");
    $("#doc").html("<h3>Documento</h3><p>----</p>");
    $("#ul-metaarea-documents").html("-");
}

$( document ).ready(function(){
    $("nav>div>div>ul>li").attr('unselectable', 'on').css('user-select', 'none').on('selectstart', false);
    reset();
    loaderEventArea();    
});

function loaderDocArea(numberEvent) {
    toReset = true;
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
                if (k.localeCompare(urlCurrentDoc) == 0){
                    toReset = false;
                }
                result=result+"<li class='list-group-item'><a onclick='LoadDocument(\""+k+"\")'>"+v+"</a></li>";
            }
        });
        $("#DocAreaBody").html(result);
        if(toReset){
            reset();
        }
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
                if(k == "conference"){
                    result=result+"<li class='list-group-item'>"+v+"</li>";
                }
                if(k == "chairs"){
                    result=result+"<li class='list-group-item'>"+v+"</li>";
                }
                if(k == "pc_members"){
                    result=result+"<li class='list-group-item'>"+v+"</li>";
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
        $("#EventAreaBody").html(result);
      },
      error: function() {
        $("#EventAreaBody").html("Error!");
      }
    });  
}

function LoadAnnotation(urlDocument){
    $("#Anntable").html("<thead><tr><th>User</th><th>Data</th><th>Content</th><th>Delete</th></tr</thead>");
    $("#ul-metaarea-ann").html("");
    $.ajax({
        url:"./PHP/loadAnnotation.php",
        type:"POST",
        data:{localUrl : urlDocument},
        dataType:'json',
        success:function(paper_json){
            $.each(paper_json,function(k,v){
                var html = $(v["Path"]).html();
                html = html.substring(0, parseInt(v["OffsetFromStart"])) + "<span id='comment"+v["Data"]+"' style='"+ randomCSS() +"' data-toggle='tooltip' title='"+v["Annotation"]+"'>" + html.substring(parseInt(v["OffsetFromStart"]),parseInt(v["LenghtAnnotation"])+parseInt(v["OffsetFromStart"]))+"</span>"+html.substring( parseInt(v["OffsetFromStart"]) + parseInt(v["LenghtAnnotation"]));
                $(v["Path"]).html(html);
                $('[data-toggle="tooltip"]').tooltip();  
                dataAnn = new Date(parseInt(v["Data"]));
                $('#Anntable').append("<tr id='row"+v["Data"]+"'><td>"+v['Author']+"</td><td>"+dataAnn.getDay() + "/"+(dataAnn.getMonth()+1)+"/"+dataAnn.getFullYear() +"<td>"+v['Annotation']+"</td></td><td onclick=deleteAnnotation(\""+v['Author']+"\",\""+v['Data']+"\") class='pointer'>delete</td></tr>");
                $("#ul-metaarea-ann").append("<li><a onclick='ScrollToAnnotation(\"comment"+v["Data"]+"\")'>"+ v["Annotation"] +"</a></li>");
            });
        },
        error:function(jqXHR, status, errorThrown) {
            console.log(jqXHR.responseText);
            console.log(status);
            console.log(errorThrown);
        }
    });
}

function deleteAnnotation(author,data){
    $.ajax({
        url:"./PHP/deleteAnnotation.php",
        type:"POST",
        data:{author : author , data: data},
        dataType:'json',
        success:function(paper_json){
            if(paper_json["error"]){
                Notify("error",paper_json["error"]);
            }else{
                Notify("success","Annotazione eliminata!");
                $('#row'+data).remove();
                $span = $('#comment'+data);
                $span.replaceWith($span.html());
                
            }
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
            $.each(paper_json["keyword"],function(k,v){
                metadati = metadati + startmetadati + v + endmetadati;
            });
            
            metadati = metadati + "</ul>"
            
            metadati = metadati + startmetadati + "Autori:<ul class='list-group list-unstyled'>";
            
            $.each(paper_json["Autori"],function(k,v){
                if(v["linked"] == "false"){
                    metadati = metadati + startmetadati;
                    metadati = metadati + "<a mailto =\""+v["email"]+"\">"+v["name"]+"</a><p>"+v["affiliation"]+"</p>";
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
    urlCurrentDoc = urlDocument;
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


function AddAnnotation(checklog){
    if(checklog){
        var selection = window.getSelection();
        if(selection.toString().length != 0){
            var selectedText = selection.toString();
            var range = selection.getRangeAt(0);
            var tempContainer = range.startContainer;
            var check = false;
            var length = 0;
            content = "";
            if (range.startContainer == range.endContainer){
                length = selection.toString().length;
            }else{
                length = range.startContainer.length;
            }
            
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
            if(check){
                $("#Annotation-content").val(selectedText);
                $("#Path").val(content);
                $("#OffsetFromStart").val(range.startOffset);
                $("#LenghtAnnotation").val(length);
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


function ViewAnnotation(){
    $("#ViewAnnotationModal").modal({
        show: 'true'
    });
}

function OpenHelp(){
    $("#ViewHelp").modal({
        show: 'true'
    });
}

function ScrollToAnnotation(idcomment){
    var offset = $(".well").offset();
    var offsetTop = offset.top - $(".navbar-fixed-top").height();
    
    $('body,html').animate({
        scrollTop: offsetTop
    },200);
    
    $('.well').animate({
        scrollTop: $("#"+idcomment).position().top + $(".well").scrollTop()
    },200);

}



