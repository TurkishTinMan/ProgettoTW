var urlCurrentDoc = "";
var currentEvent = -1;
var eventrole = "";
var helpUrl = "../help.html";
var AnnotatorSign = '<span class="glyphicon glyphicon-pencil"></span>';
var PC_MemberSign = '<span class="glyphicon glyphicon-eye-open"></span>';
var NoneSign = '<span class="glyphicon glyphicon-ban-circle"></span>';
var tempannotations = [];


function randomCSS(){
    return "background-color:yellow;";
}

//carica il file help.html che contiene la guida
function reset(){
    LoadDocument(helpUrl);
}

//Evidenzia l'evento scelto
function HighLightEvent(i){
    $("#EventAreaBody > li").removeClass("highlight");
    currentEvent = i;
    $("#"+currentEvent+"event").parent().addClass("highlight");
}

//Evidenzia il documento scelto, lo setta come variabile globale, riempie le form di riferimento e svuota i metadati
function HighLightDocument(urlDocument,element){
    $("#DocAreaBody > li").removeClass("highlight");
    $(element).parent().addClass("highlight");
    $("#keyWordsList").html("");
    $("#ACM").html("");
    
    $("li.hiddenuntilAnnotator").css('display','none');
    $("#Role").html("Reader");
    
    $("#Anntable").html("");
    $("#chairjudgmentresume").html("");;
    $("#ul-reviewer").html("");
    $("#ul-authors").html("");
    $("#resumereviewers").html("");
    tempannotations = [];
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
                    result=result+"<li class='list-group-item member'><a  id=\""+i+"event\" onclick='ChangeEvent("+i+")'>"+v+"</a></li>";
                }
            });
        }
        $("#EventAreaBody").html(result);
        if (currentEvent >= 0){
            ChangeEvent(currentEvent);
        }else{
            LoadDocument(helpUrl);
        }
      },
      error: function() {
        Notify('error','Errore interno, impossibile caricare gli eventi!');
      }
    });  
}

//Funzione cambia evento
function ChangeEvent(json_data_event){
    HighLightEvent(json_data_event);
    loaderDocArea(json_data_event);
    loaderMetaEventArea(json_data_event);
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
            paper = "<div class=\"text-center\"><h1>" + paper_json["title"] + "</h1>";
            if(paper_json["subtitle"]){
                paper = paper + "<h4>"+paper_json["subtitle"]+"</h4></div>";
            }
            paper = paper + "<div id=\"docBody\">" + paper_json["body"] + "</div>";
            $("#doc").html(paper);
            if(urlDocument != helpUrl){                
                // Keywords    
                if (paper_json["keyword"].length > 0) {
                    KeyWordManager(paper_json["keyword"]);
                }

                //Lista delle ACM
                if (paper_json["ACM"].length > 0) {
                    ACMManager(paper_json["ACM"]);
                }

                //Creazione lista degli autori
                AuthorManager(paper_json["Autori"]);
                //Creazione lista degli reviwer -- TODO metterla da qualche parte
                ReviewerManager(paper_json["reviewersjudge"]);   


                resumechairjudgment = "Inespresso";

                if(paper_json["chairJudgmentvalue"]){
                    resumechairjudgment = paper_json["chairJudgmentvalue"];
                }
                
                  
                if(paper_json["chairJudgment"]){
                    Notify('info','Sei un chair, il tuo giudizio su questo documento è:'+ resumechairjudgment +'.<br>Puoi cambiarlo cliccando vicino al tuo nome nella lista dei reviewer.');
                }
                
                
                switch(resumechairjudgment){
                    case 'Rejected':
                        resumechairjudgment = '<span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>'
                        break;
                    case 'Accepted':
                        resumechairjudgment = '<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>'
                        break;
                    default:
                        resumechairjudgment = '<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>';
                        break;
                }

                
                
                if(paper_json["chairJudgment"]){
                    resumechairjudgment = "<a onclick='ViewChairJudgment()'>" + resumechairjudgment + "</a>";
                }

                $("#chairjudgmentresume").html("<h3> Stato documento:" + resumechairjudgment + "</h3><hr>");

                AnnotationManager(paper_json["annotations"]);

            }
        },
        error:function(jqXHR, status, errorThrown) {
            console.log(jqXHR.responseText);
            console.log(status);
            console.log(errorThrown);
        }
    });
}


function ReviewerManager(list){
    $.each(list, function(z,x){
        judge = x['judge'];
        if(x['own'] == "true"){
            judge = "<a  onclick='JudgmentModal()'>"+judge+"</a>";
            Notify('info','Sei un revier, il tuo giudizio su questo documento è:'+judge+'.<br>Puoi cambiarlo cliccando vicino al tuo nome nella lista dei reviewer.');
        }                
        start = z.indexOf('<');
        start = start +1;
        end = z.indexOf('>');
        y = z.substring(start,end);
        y = y.replace(/@/g, 'a');
        y = y.replace(/\./g, 'p');
        $("#ul-reviewer").append("<li id="+y+">"+z.substring(0,start-2)+"<span id='Judgment'>"+judge+"</span></li><hr>");
        $("#resumereviewers").append("<li>"+ $('#'+ y).html() +"</li>");
    });
}



function AuthorManager(list){
    metadati = "<p><strong>Authors</strong></p>";
    $.each(list,function(k,v){
        if(v["name"] && v["linked"]!= "true"){
            metadati + "<li>";
            if(v["email"]){
                metadati = metadati + "<a href=\"mailto:"+v["email"]+"\">"+v["name"]+"</a>";
            }else{
                metadati = metadati + v["name"];
            }
            if(v["affiliation"]){
                metadati = metadati + "<br /><small>"+v["affiliation"]+"</small>";
            }
            metadati = metadati + "</li><br />";
            
        }
    });
    metadati = metadati + "<hr>";
    $("#ul-authors").append(metadati);
}

function KeyWordManager(list){
    var list_of_keywords = $("<ul class=\"list-inline\"></ul>");
    $.each(list,function(k,v) {
        list_of_keywords.append("<li><code>" + v + "</code></li>");
    });
    $("<p class=\"keywords\"><strong>Keywords</strong></p>").append(list_of_keywords).appendTo("#keyWordsList");
}


function ACMManager(list){
    var list_of_categories = $("<p class=\"acm_subject_categories\"><strong>ACM Subject Categories</strong></p>");
    $.each(list, function(x,z){
        list_of_categories.append("<br /><code>" + z.split(",").join(", ") + "</code>");
    });
list_of_categories.appendTo("#ACM");
}

function AnnotationManager(json_ann){
    $("#Anntable").html("<thead><tr><th>User</th><th>Data</th><th>Content</th><th>Find</th><th>Delete</th></tr></thead>");
    $.each(json_ann,function(i,annotation){
        AddAnnotationToTable(annotation["Data"] ,annotation["Author"], annotation["Comment"], (annotation["Author"] == $("#Author").val()));    
        AddAnnotationToText (annotation["Data"],annotation["Target"]["Path"],annotation["Target"]["OffsetFromStart"],annotation["Target"]["LenghtAnnotation"],annotation["Comment"]);
        tempannotations.push(annotation);
    });
}


function AddJudgmentRw(){
    $.ajax({
      url: "./PHP/AddJudgmentRw.php",
      type: "POST",
      data: {localUrl : urlCurrentDoc, 
             judge : $('input[name=judgment]:checked', '#reviewer').val()
            },
      dataType: 'json',
      success: function(json_data){
        temp = $(".highlight");
        LoadDocument(urlCurrentDoc);
        console.log(json_data["var"]);
        temp.addClass('highlight')
        Notify(json_data["esito"],json_data["content"]);
      },
      error: function(jqXHR, status, errorThrown) {
        Notify('error','Errore interno, impossibile caricare gli eventi!');
        console.log(jqXHR.responseText);
        console.log(status);
        console.log(errorThrown);

      }
    });  

    $('#ViewJudgmentModal').modal('toggle');
}

function AddJudgmentCH(){
    $.ajax({
      url: "./PHP/AddJudgmentCH.php",
      type: "POST",
      data: {localUrl : urlCurrentDoc, 
             judge : $('input[name=judgment]:checked', '#chairform').val()
            },
      dataType: 'json',
      success: function(json_data){
        temp = $(".highlight");
        LoadDocument(urlCurrentDoc);
        console.log(json_data["var"]);
        temp.addClass('highlight')
        Notify(json_data["esito"],json_data["content"]);
      },
      error: function(jqXHR, status, errorThrown) {
        Notify('error','Errore interno, impossibile caricare gli eventi!');
        console.log(jqXHR.responseText);
        console.log(status);
        console.log(errorThrown);

      }
    });  

    $('#ViewChairJudgment').modal('toggle');
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


function AddAnnotation(){
    if(helpUrl != urlCurrentDoc){
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
        Notify("error", "Non hai i permessi per creare annotazioni su queto documento");
    }
}

function AddAnnotationToTable(date,author,content,check){
    datetoappend = "<tr id='row"+date+"'>";
    datetoappend = datetoappend + "<td>"+author+"</td>";
    dataAnn = new Date(parseInt(date));
    month = parseInt(dataAnn.getMonth()) + 1;
    datetoappend = datetoappend + "<td>"+dataAnn.getDate() + "/" + month + "/" + dataAnn.getFullYear() + "</td>";
    datetoappend= datetoappend +"<td>"+content+"</td>";
    datetoappend= datetoappend +"<td><a onclick='ScrollToAnnotation(\"comment"+date+"\")' class='pointer'><span class='glyphicon glyphicon-search' aria-hidden ='true'></span></a></td>";
    datetoappend= datetoappend +"<td onclick=deleteAnnotationLocal(\""+date+"\",\""+check+"\") class='pointer'><span class='glyphicon glyphicon-trash' aria-hidden ='true'></span></td>";
    datatoappend=datetoappend+"</tr>";
    $("#Anntable").append(datatoappend);
}

function AddAnnotationToText(date,path,offset,length,comment){
    var html = $(path).html();
    newhtml = html.substring(0, parseInt(offset));
    newhtml = newhtml + "<span id='comment"+date+"' style='"+ randomCSS() +"' data-toggle='tooltip' title='"+comment+"'>";
    newhtml = newhtml + html.substring(parseInt(offset),parseInt(offset) + parseInt(length));
    newhtml = newhtml + "</span>";
    newhtml = newhtml + html.substring(parseInt(offset) + parseInt(length));
    
    $(path).html(newhtml);
    $('[data-toggle="tooltip"]').tooltip(); 
    
}


function AddAnnotationLocal(){
    thisannotation = {};
    thisannotation["Target"]={"Path":$("#Path").val(),                                  "OffsetFromStart":$("#OffsetFromStart").val(),"LenghtAnnotation":$("#LenghtAnnotation").val()}
    thisannotation["Data"]= $("#Data").val();
    thisannotation["Comment"]= $("#Comment").val();
    thisannotation["Author"]= $("#Author").val();
    AddAnnotationToTable(thisannotation["Data"] ,thisannotation["Author"], thisannotation["Comment"], true);    
    AddAnnotationToText (thisannotation["Data"],thisannotation["Target"]["Path"],thisannotation["Target"]["OffsetFromStart"],thisannotation["Target"]["LenghtAnnotation"],thisannotation["Comment"]);
    tempannotations.push(thisannotation);    
    $('#AnnotationModal').modal('toggle');
}

function DeleteAnnotationFromTable(date){
    $('#row'+date).remove();
}


function DeleteAnnotationFromText(date){
    $span = $('#comment'+date);
    $span.replaceWith($span.html());
}




function deleteAnnotationLocal(date,author){
    if(author == "true"){
        DeleteAnnotationFromTable(date);
        DeleteAnnotationFromText(date);
        $.each(tempannotations, function(number,annotation){
            if(annotation["Data"]==date){
                tempannotations.splice(number,1);
            }
        });
    }
}


function SaveAnnotation(){
    $.ajax({
      url: "./PHP/SaveAnnotation.php",
      type: "POST",
      data: {localUrl : urlCurrentDoc, annotations : tempannotations},
      dataType: 'json',
      success: function(json_data){
        tempannotations = [];
        Notify(json_data["esito"],json_data["content"]);
      },
      error: function(jqXHR, status, errorThrown) {
        Notify('error','Errore interno, impossibile caricare gli eventi!');
        console.log(jqXHR.responseText);
        console.log(status);
        console.log(errorThrown);

      }
    });  
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


function ChangeMode(){
    $.ajax({
      url: "./PHP/changemode.php",
      type: "POST",
      data: {localUrl : urlCurrentDoc},
      dataType: 'json',
      success: function(json_data){
        if(json_data["esito"] == "success"){
            if($("li.hiddenuntilAnnotator").css('display') == "none"){
                $("li.hiddenuntilAnnotator").css('display','block');
                $("#Role").html("Annotator");
            }else{
                $("li.hiddenuntilAnnotator").css('display','none');
                $("#Role").html("Reader");
            }
        }
        Notify(json_data["esito"],json_data["contenuto"]);
      },
      error: function(jqXHR, status, errorThrown) {
        Notify('error','Errore interno, impossibile caricare gli eventi!');
        console.log(jqXHR.responseText);
        console.log(status);
        console.log(errorThrown);

      }
    });  
}



