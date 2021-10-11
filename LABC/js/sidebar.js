

$(document).ready(function () {
   loadOptions();

});

var open = "icone fecha glyphicon glyphicon-minus-sign";
var close = "icone abre glyphicon glyphicon-plus-sign";

var options =[
    {
        nome:"Inicial",
        id:"inicial",
        redir:true,
    },
    {
        nome:"Game Engines",
        id:"list",
        subs:[
            {
                nome:"Unity",
                id:"unity",
                redir:true
            },
            {
                nome:"Unreal Engine",
                id:"unreal",
                redir:true,
            },
            {
                nome:"GameMaker: Studio",
                id:"gamemaker",
                redir:true,
            },
            {
                nome:"Outras",
                id:"outros",
                redir:true
            },
        ]


    }

];
function getPageName(hash){
    var retorno = "Pagina não encontrada";
    options.forEach(function (t) {
        if(t.subs!=undefined){
            t.subs.forEach(function (t2) {
                if(t2.id===hash){
                  retorno =t2.nome;
                }
            })
        }else{

            if(t.id===hash){
                retorno= t.nome;
            }
        }

    });
    return retorno;

}
function append(campo,html){
    append(campo,html,false);
}
function append(campo,html,issub){

    html+= "<li>";
    if(campo.subs != undefined){

        html+='<span class=\"menu\" id=\"sub-'+campo.id+'\" onClick=\"clickSubMenu(\''+campo.id+'\')\">'+campo.nome+
            '<span><img class ="icone" src =\"./img/plus.png\">' +
            '</span>' +
            '</span>';

        html+="<ul style=\"display:none;\" id=\"subul-"+campo.id+"\" >";
        campo.subs.forEach(function (t, number) {
            html= append(t,html,true);
        });

        html+="</ul>";
    }else{
        var nome = campo.nome;
        if(issub){
            nome = "* "+nome;
        }
        html+='<span id =\'id-'+campo.id+'\' class=\"menu\" onClick=\"clickMenu(\''+campo.id+'\')\">'+nome+'</span>';
    }

    html+="</li>";

    return html;
}

function loadOptions(){


    var html = "";
    options.forEach(function (t, number) {
        html = append(t,html);
    });
    $("#options").html(html);


}



function loadedPage(id){
    setActive(id);
}

function clickMenu(id){
    setActive(id);
    window.location.hash = id;
    reloadPage();
}

function setActive(id){
    openMenuToActive(id);
    $(".active").removeClass("active");
    $("#id-"+id).addClass("active");
}

function openMenuToActive(id){
   options.forEach(function (t) {
       if(t.subs!=undefined){
           t.subs.forEach(function (t2) {

               if(t2.id===id){

                    if(!isMenuOpen(t.id)) {
                        setMenuOpen(t.id, true);
                    }
               }
           })

       }

   })
}
function setMenuOpen(id,isopen){
    var e= $("#subul-"+id);
    var spanicon = $("#sub-"+id).children("span");
    if(!isopen){
        e.hide("slide",function () {
            spanicon.html('<img class ="icone" src =\"./img/plus.png\">');
        });
    }else{
        e.show("slide",function () {
            spanicon.html('<img class =\"icone\" src =\"./img/minus.png\">');
        });
    }
}

function isMenuOpen(id){
    var e= $("#subul-"+id);
    var display = e.is(":visible");
    return display;
}

function clickSubMenu(id){
    if(isMenuOpen(id)){
        setMenuOpen(id,false);
    }else{
        setMenuOpen(id,true);
    }
}

