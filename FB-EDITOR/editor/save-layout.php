<?php

$data = file_get_contents("php://input");

$json = json_decode($data, true);

if(!$json){
    echo "JSON non valido";
    exit;
}

$tema = $json["meta"]["tema"] ?? "default";
$page = $json["meta"]["page"] ?? "pagina";

$file = "APP_ROOT/data/".$page.".json";


file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT));

echo "SALVATO";

?>