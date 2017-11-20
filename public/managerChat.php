<?php

$type = $_POST["type"];

$messages = array();


switch ($type) {
    case "sendMsg":
        array_push($messages, $_POST["msg"]);
        echo var_dump($messages);
        break;
    case "receiveMsg":
        echo var_dump($messages);
        break;
    case "getHtml":
        echo file_get_contents("chathtm.php");
        break;
    default :
        break;
}