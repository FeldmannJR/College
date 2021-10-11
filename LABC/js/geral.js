

var playing = [];

function playSound(sound)
{

    if(playing.indexOf(sound)>-1){
        return;
    }
    var audio = new Audio("./sound/"+sound);
    audio.play();
    playing.push(sound);
    audio.addEventListener("ended",function(){
     remove(playing,sound);
    });
}

function remove(arr, what) {
    var found = arr.indexOf(what);

    while (found !== -1) {
        arr.splice(found, 1);
        found = arr.indexOf(what);
    }
}
