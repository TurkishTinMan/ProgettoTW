var urlCurrentDoc = "";
var currentEvent = 0;
var eventrole = "";
var helpUrl = "../help.html";
var AnnotatorSign = '<span class="glyphicon glyphicon-pencil"></span>';
var PC_MemberSign = '<span class="glyphicon glyphicon-eye-open"></span>';
var NoneSign = '<span class="glyphicon glyphicon-ban-circle"></span>';


function randomCSS(){
    return "background-color:#"+Math.floor(Math.random()*16777215).toString(16);
}

//carica il file help.html che contiene la guida
function reset(){
    LoadDocument(helpUrl);
}

//Evidenzia l'evento scelto
function HighLightEvent(element){
    $("#EventAreaBody > li").removeClass("highlight");
    $(element).parent().addClass("highlight");
}

//Evidenzia il documento scelto, lo setta come variabile globale, riempie le form di riferimento e svuota i metadati
function HighLightDocument(urlDocument,element){
    $("#DocAreaBody > li").removeClass("highlight");
    $(element).parent().addClass("highlight");
    $("#Doc").val(urlDocument);
    $("#Doc1").val(urlDocument);
    $("#Doc2").val(urlDocument);
    $("#keyWordsList").html("");
    $("#Anntable").html("");
    $("#chairjudgmentresume").html("");
    /*
    $("span#Judgment").html(" ");
    $("#resumereviewers").html(" ");
    $("#chairjudgmentresume").html(" ");
    $("#ul-reviewer").remove();
    $("#Reviewer-title").remove();*/
    urlCurrentDoc = urlDocument;
}

//Switch che associa la stringa "role" alla variabile e al simbolo grafico
function ChangeRole(role){
    switch(role){
        case 'Chair':
            $("#eventRole").html(AnnotatorSign);
            eventrole = "Chair";
            break;
        case 'PC Member':
            $("#eventRole").html(PC_MemberSign);
            eventrole = "";
            break;
        default:
            $("#eventRole").html(NoneSign);
            eventrole = "";
            break;
    }
}

//funzione ready
$( document ).ready(function(){
    //Mi permette di cliccare sul menu senza perdere la selezione sul testo
    $("nav>div>div>ul>li").attr('unselectable', 'on').css('user-select', 'none').on('selectstart', false);
    loaderEventArea();  
    $("#eventRole").html(NoneSign);
    reset();
});


//funzione ajax che restituisce la lista degli eventi che l'utente può vedere
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
                    result=result+"<li class='list-group-item member'><a  onclick='ChangeEvent(\""+i+"\",this)'>"+v+"</a></li>";
                }
            });
        }
        $("#EventAreaBody").html(result);
      },
      error: function() {
        Notify('error','Errore interno, impossibile caricare gli eventi!');
      }
    });  
}

//Funzione cambia evento
function ChangeEvent(json_data_event,e){
    HighLightEvent(e);
    currentEvent = json_data_event;
    $("#Eventid").val(currentEvent);
    $("#Eventid1").val(currentEvent);
    $("#Eventid2").val(currentEvent);
    loaderDocArea(currentEvent);
    loaderMetaEventArea(currentEvent);
}

//Funzione Ajax che restiruisce tutti i documenti dell'evento
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
                ChangeRole(v);
            }else{
                if (k.localeCompare(urlCurrentDoc) == 0){
                    toReset = false;
                    result=result+"<li class='list-group-item highlight'><a onclick='LoadDocument(\""+k+"\",this)'>"+v+"</a></li>";
                }else{
                    result=result+"<li class='list-group-item'><a onclick='LoadDocument(\""+k+"\",this)'>"+v+"</a></li>";
                }
            }
        });
        $("#DocAreaBody").html(result);
        if(toReset){
            reset();
        }
      },
      error: function() {
        Notify('error','Errore interno, impossibile caricare i documenti!');
      }
    });  
    $("#DocArea").show(300);
}

//Funzione Ajax che restiruisce i metadati dell'evento
function loaderMetaEventArea(numberEvent){
    if(numberEvent < 0){
        reset();
    }else{
        $.ajax({
          url: "./PHP/loaderMetaEventArea.php",
          type: "POST",
          data: {numberEvent : numberEvent},
          dataType: 'json',
          success: function(json_data){
            result = "";
            $.each(json_data,function(k,v){
                //nome conferenza--TODO metterla da qualche parte o non gestire il caso
                if(k == "conference"){
                    result=result+"<li>"+v+"</li>";
                }
                //lista dei Chair-- TODO Farla vedere da qualche parte
                if(k == "chairs"){
                    result = result + "<h4>Chairs</h4>";
                    $.each(v,function(x,z){
                        result=result+"<li>"+z+"</li>";    
                    });
                }
                //lista dei membri-- TODO metterla da qualche parte o non gestire il caso
                if(k == "pc_members"){
                    result = result + "<h4>Membri</h4>";
                    $.each(v,function(x,z){
                        start = z.indexOf('<');
                        start = start +1;
                        result=result+"<li>"+z.substring(0,start-1)+"</li>";    
                    });
                }
                
            });
          },
          error: function() {
              Notify('error','Impossibile raggiungere le caratteristiche dell\'evento, errore interno!');
          }
        });  
    }
}

//Funzione ajax che carica il documento
function LoadDocument(urlDocument,e) { 
    HighLightDocument(urlDocument,e);
    $.ajax({ 
        url:"./PHP/loaderDocument.php",
        type:"POST",
        data: {
                localUrl : urlDocument,
                currentEvent : currentEvent
        },
        dataType:'json',
        success: function(paper_json) {
            //Scrittura del paper nell'apposita sezione
            paper = "<h1>" + paper_json["title"] + "</h1><div>" + paper_json["body"] + "</div>";
            $("#doc").html(paper);
            if(urlDocument != helpUrl){
                startmetadati = "<li>";
                endmetadati="</li>";

                //Lista delle parole chiavi del documento
                $.each(paper_json["keyword"],function(k,v){
                    $(startmetadati + v + endmetadati).appendTo("#keyWordsList");
                });

                //Creazione lista degli autori -- TODO metterla da qualche parte
                metadati = "<ul class='list-group list-unstyled'>";
                $.each(paper_json["Autori"],function(k,v){
                    if(v["linked"] == "false"){
                        metadati = metadati + startmetadati;
                        metadati = metadati + "<a href=\"mailto:"+v["email"]+"\">"+v["name"]+"</a><p>"+v["affiliation"]+"</p>";
                        metadati = metadati + endmetadati;
                    }
                });
                metadati = metadati + "</ul>";
                metadati = metadati;

                //Creazione lista degli reviwer -- TODO metterla da qualche parte
                result = "<ul id='ul-reviewer' class='list-group list-unstyled'></ul>";
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


                resumechairjudgment = "Inespresso";

                if(paper_json["chairJudgmentvalue"]){
                    resumechairjudgment = paper_json["chairJudgmentvalue"];
                }  
                if(paper_json["chairJudgment"]){
                    resumechairjudgment = "<a onclick='ViewChairJudgment()'>" + resumechairjudgment + "</a>";
                    Notify('info','Sei un chair, il tuo giudizio su questo documento è:'+ resumechairjudgment +'.<br>Puoi cambiarlo cliccando vicino al tuo nome nella lista dei reviewer.');
                }

                $("#chairjudgmentresume").html("<h3>Chair's judgement:" + resumechairjudgment + "</h3>");


                LoadAnnotation(urlDocument);  
            }
        },
        error:function(jqXHR, status, errorThrown) {
            console.log(jqXHR.responseText);
            console.log(status);
            console.log(errorThrown);
        }
    });
}












function LoadAnnotation(urlDocument){
    $("#Anntable").html("<thead><tr><th>User</th><th>Data</th><th>Content</th><th>Find</th><th>Delete</th></tr></thead>");
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
                $('#Anntable').append("<tr id='row"+v["Data"]+"'><td>"+v['Author']+"</td><td>"+dataAnn.getDay() + "/"+(dataAnn.getMonth()+1)+"/"+dataAnn.getFullYear() +"<td>"+v['Annotation']+"</td><td><a onclick='ScrollToAnnotation(\"comment"+v["Data"]+"\")' class='pointer'><span class='glyphicon glyphicon-search' aria-hidden ='true'></span></a></td><td onclick=deleteAnnotation(\""+v['Author']+"\",\""+v['Data']+"\") class='pointer'><span class='glyphicon glyphicon-trash' aria-hidden ='true'></span></td></tr>");
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
                giudizio = "Inespresso";
                if(paper_json["judgment"]){
                    giudizio = paper_json["judgment"];
                }
                $('#'+ z + "> #Judgment").html("<a  onclick='JudgmentModal()'>"+giudizio+"</a>");
                Notify('info','Sei un revier, il tuo giudizio su questo documento è:'+giudizio+'.<br>Puoi cambiarlo cliccando vicino al tuo nome nella lista dei reviewer.')
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



function ShowHideArea(idshow){
    if($(idshow).is(":visible")){
        $(idshow).hide(300);
    }else{
        $(idshow).show(300);
    }
}

function ChangePage(idpagebutton){
    $( document ).ready(function(){
        $(idpagebutton).click();
    });
}

function Notify(type,text){
    $( document ).ready(function(){
        output = "<div class='alert fade in alert-dismissable alert-";
        switch(type){
            case 'error':
                output = output + "danger";
            break;
            case 'success' :
                output = output + "success";
            break;
            case 'info':
                output = output + "info";
        }
        output = output + "'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+ text +"</div>";
        $(output).appendTo("#notification").fadeTo(5000, 500).slideUp(300, function(){
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
                Notify("info", "Devi selezionare qualcosa dentro lo stesso frammento");
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
/*
function OpenHelp(){
    $("#ViewHelp").modal({
        show: 'true'
    });
}
*/
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



