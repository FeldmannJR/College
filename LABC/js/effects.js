


var effects = [
    {
        nome:"glow1",
        colors:["#00FF00","#FF0000","#0000FF"],
        index: 0,
        tempo:1000
    },
    {
        nome:"glow2",
        colors:["#FFFFFF","#FF0000"],
        index: 0,
        tempo:250
    }

];
function faz(classe,cor,tempo){
    $("."+classe).animate(
        {
            color:cor

        },tempo);
}
$(document).ready(function () {
 effects.forEach(function (t) {
     setInterval(function () {

         if(t.index>=t.colors.length){
             t.index = 0;
         }
         var next = t.colors[t.index];
         faz(t.nome,next,t.tempo);
         t.index++;

     },t.tempo);

 });


});


