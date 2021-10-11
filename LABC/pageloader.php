<?php
$page = $_POST["page"];

$c = $page{0};

if(strcasecmp($c,"#")==0){
    $page = substr($page,1);
}

loadFromFile($page);



function loadFromFile($page ){
    if(file_exists("./pages/".$page.".php")) {
        include './pages/' . $page . '.php';
    }else{
        include './pages/error.php';

    }
}


?>