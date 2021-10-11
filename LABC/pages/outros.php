<?php
class Engine{
    public $img;
    public $nome;
    public $link;
    public $desc;

    public  function __construct()
    {
        $args= func_get_args();
        $this->img = $args[0];
        $this->link = $args[2];
        $this->nome = $args[1];
        if(count($args)>3){
            $this->desc = $args[3];
        }


    }
}
$engines =array(
    new Engine("source.png","Source 2","http://source.valvesoftware.com/","Valve"),
    new Engine("blender.png","Blender","https://www.blender.org/"),
    new Engine("jmonkey.png","jMonkeyEngine","http://jmonkeyengine.org/"),
    new Engine("rockstar.png","Rockstar Advanced Game Engine","https://www.rockstargames.com/"),
    );


?>
<title>Outras</title>
<h1>Outros motores</h1>
<div class="linha" style="margin-bottom: 10px;"></div>
<div class="jogos">
    <?php
    foreach($engines as $engine){
        echo "<div class=\"border\">";
        echo "<div style=\"background-image:url(./img/outros/".$engine->img.");\">";
        echo "<div class=\"overlay\" onclick='document.location=\"".$engine->link."\"'>";
        echo "<h3>".$engine->nome."</h3>";
        if(isset($engine->desc)){
            echo "<p>".$engine->desc."</p>";
        }
        echo "</div></div></div>";

    }
?>

    <div class="border">
        <div style="background-image:url(./img/outros.jpg);">
            <div class="overlay" onclick='document.location="https://en.wikipedia.org/wiki/List_of_game_engines"'>
                <h3>Outros</h3>
            </div>
        </div>
    </div>
</div>