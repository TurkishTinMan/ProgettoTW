var urlCurrentDoc = "";
var currentEvent = 0;

function randomCSS(){
    return "background-color:#"+Math.floor(Math.random()*16777215).toString(16);
}


function reset(){
    $("#doc").html("<h3>Documento</h3><p>----</p>");
    $("#ul-metaarea-documents").html(" ");
    $("#ul-reviewer").remove();
    $("#Reviewer-title").remove();
}

$( document ).ready(function(){
    $("nav>div>div>ul>li").attr('unselectable', 'on').css('user-select', 'none').on('selectstart', false);
    reset();
    loaderEventArea();    
});

function loaderDocArea(numberEvent) {
    toReset = true;
    if ($("#DocArea").css("display") != 'none'){
        $("#DocArea").hide(300);
    }
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
    $("#DocArea").show(300);
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
                    result=result+"<li>"+v+"</li>";
                }
                if(k == "chairs"){
                    result = result + "<h4>Chairs</h4>";
                    $.each(v,function(x,z){
                        result=result+"<li>"+z+"</li>";    
                    });
                }
                if(k == "pc_members"){
                    result = result + "<h4>Membri</h4>";
                    $.each(v,function(x,z){
                        start = z.indexOf('<');
                        start = start +1;
                        result=result+"<li>"+z.substring(0,start-1)+"</li>";    
                    });
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
                    result=result+"<li class='list-group-item member'><a onclick='ChangeEvent(\""+i+"\")'>"+v+"</a></li>";
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
    $("#metaarea-ann").html(" ");
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
                $("#metaarea-ann").append("<p>"+ v["Annotation"] +": "+ v['Author'] +"<a onclick='ScrollToAnnotation(\"comment"+v["Data"]+"\")'>Trovalo nel testo</a></p><hr>");
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

function loadJudgment(userkey){
    $.ajax({
        url:"./PHP/loaderJudgment.php",
        type:"POST",
        data: {localUrl : urlCurrentDoc, user : userkey},
        dataType:'json',
        success: function(paper_json) {
            start = userkey.indexOf('<');
            start = start +1;
            end = userkey.indexOf('>');
            z = userkey.substring(start,end);
            z = z.replace(/@/g, 'a');
            z = z.replace(/\./g, 'p');
            if(paper_json["role"] == "reviewer"){
                if(paper_json["judgment"]){
                    $('#'+ z + "> #Judgment").html("<a  onclick='JudgmentModal()'>"+paper_json["judgment"]+"</a>");
                }else{
                    $('#'+ z + "> #Judgment").html("<a  onclick='JudgmentModal()'>Inespresso</a>");
                    JudgmentModal();
                }
            }else{
                if(paper_json["judgment"]){
                    $('#'+ z + "> #Judgment").html(paper_json["judgment"]);
                }else{
                    $('#'+ z + "> #Judgment").html("Inespresso");
                }   
            }
            $("#resumereviewers").append("<li>"+ $('#'+ z).html() +"</li>");
        },
        error:function(jqXHR, status, errorThrown) {
            console.log(jqXHR.responseText);
            console.log(status);
            console.log(errorThrown);
        }
    });
}


function LoadDocument(urlDocument) {  
    $("span#Judgment").html(" ");
    $("#resumereviewers").html(" ");
    $("#chairjudgmentresume").html(" ");
    $("#ul-reviewer").remove();
    $("#Reviewer-title").remove();
    urlCurrentDoc = urlDocument;
    $.ajax({ 
        url:"./PHP/loaderDocument.php",
        type:"POST",
        data: {localUrl : urlDocument,currentEvent : currentEvent},
        dataType:'json',
        success: function(paper_json) {
            paper = "<h1>" + paper_json["title"] + "</h1><div>" + paper_json["body"] + "</div>";
            $("#doc").html(paper);
            
            startmetadati = "<li>";
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
                    metadati = metadati + "<a href=\"mailto:"+v["email"]+"\">"+v["name"]+"</a><p>"+v["affiliation"]+"</p>";
                    metadati = metadati + endmetadati;
                }
            });
            metadati = metadati + "</ul>";
            metadati = metadati + endmetadati;
            $("#ul-metaarea-documents").html(metadati);
            $("#Doc2").val(urlDocument);
            $("#Doc1").val(urlDocument);
            $("#Doc").val(urlDocument);
            LoadAnnotation(urlDocument);
            result = "<h4 id='Reviewer-title'>Reviewers</h4>";
            result = result+"<ul id='ul-reviewer' class='list-group list-unstyled'></ul>";
            $("#ul-metaarea-events").append(result);
            $.each(paper_json["reviewers"], function(x,z){
                loadJudgment(z);
                start = z.indexOf('<');
                start = start +1;
                end = z.indexOf('>');
                y = z.substring(start,end);
                y = y.replace(/@/g, 'a');
                y = y.replace(/\./g, 'p');
                $("#ul-reviewer").append("<li id="+y+">"+z.substring(0,start-2)+"<span id='Judgment'></span></li>")  
            });
            resumechairjudgment = "";
            
            if(paper_json["chairJudgmentvalue"]){
                resumechairjudgment = paper_json["chairJudgmentvalue"];
            }else{
                resumechairjudgment = "Inespresso";
            }
            
            if(paper_json["chairJudgment"]){
                console.log(paper_json["chairJudgment"]);
                resumechairjudgment = "<a onclick='ViewChairJudgment()'>" + resumechairjudgment + "</a>";
            }
            
            $("#chairjudgmentresume").html(resumechairjudgment);

            
        },
        error:function(jqXHR, status, errorThrown) {
            console.log(jqXHR.responseText);
            console.log(status);
            console.log(errorThrown);
        }
    });
    urlCurrentDoc = urlDocument;
}

function ShowHideArea(idshow){
    if($(idshow).is(":visible")){
        $(idshow).hide(300);
    }else{
        $(idshow).show(300);
    }
}

function ChangeEvent(json_data_event){
    $("#Eventid2").val(json_data_event);
    $("#Eventid1").val(json_data_event);
    $("#Eventid").val(json_data_event);
    currentEvent = json_data_event;
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
        $(output).appendTo("#notification").fadeTo(2000, 500).slideUp(500, function(){
               $(output).slideUp(500);
        });   
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

function JudgmentModal(){
    $("#ViewJudgmentModal").modal({
        show:true
    });
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

function ViewChairJudgment(){
    $("#ViewChairJudgment").modal({
        show: 'true'
    });
}


function ScrollToAnnotation(idcomment){
    var offset = $(".well").offset();
    
    $('body,html').animate({
        scrollTop: 0
    },200);
    
    $('.well').animate({
        scrollTop: $("#"+idcomment).position().top + $(".well").scrollTop()
    },200);

}



