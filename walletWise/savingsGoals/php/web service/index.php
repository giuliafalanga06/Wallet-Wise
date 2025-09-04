<?php

    header("Content-type:Application/json");
    header("Access-control-allow-methods: GET, POST, PUT, DELETE");
    header("Access-control-allow-origin: *"); 

    $method = $_SERVER["REQUEST_METHOD"]; 
    
    switch($method){
        case "get" : 
            include("get.php");
            exit();

        case "options":
            http_response_code(200); 
            exit();

        default: 
            http_response_code(405); 
            echo"{error : 'richiesta non consentita'}" ;
            echo json_encode(['error' =>'richiesta non consentita' ]);
            exit();
    }
?>