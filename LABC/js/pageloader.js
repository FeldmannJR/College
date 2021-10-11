/*Java script é bom, as pessoas que são ruins*/
var loaded;

$(document).ready(function(){

   reloadPage();
   loadedPage(removeHash(loaded));

});

function removeHash(doq){
    var tmp = doq;
    if(tmp.charAt(0)==="#"){
        tmp = tmp.substr(1);
    }
    return tmp;
}

/*
* Um script em php escolhe o arquivo da pagina e retorna a pagina, caso passe uma rgumento mto loco que não existe da pau
* */
function loadPage(page){
    loaded = page;
    $("#conteudo").html('<img style="margin:auto;display:block;margin-top:100px;" src="./img/loader.gif">');
    document.title = "Carregando";
    $.ajax({
        type: "POST",
        url: "./pageloader.php",
        data: 'page='+page,
        dataType: "html",
        success: function(msg){

            if(parseInt(msg)!=0)
            {

                var title= $(msg).filter("title:first").html();
                var html = removeElements(msg,"title");
                if(title ==undefined){
                    title ="Game Engines";
                }
                $('#conteudo').html(html);
                document.title = title;

            }
        }

    });
}
function removeElements(text, selector) {
    var wrapped = $("<div>" + text + "</div>");
    wrapped.find(selector).remove();
    return wrapped.html();
}

function reloadPage()
{
    var hash=window.location.hash;
    if(!hash){
        hash ="inicial";
    }
    if(hash != loaded)
    {

        loadPage(hash);
    }
    return hash;
}