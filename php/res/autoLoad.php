<?php
spl_autoload_register(function ($class_name) {

    $class_name = str_replace("\\","/",$class_name);
    $class_name .= ".php";

    if(file_exists(CLASSES.$class_name)){
        require(CLASSES.$class_name);
    }else{
        echo "Arquivo <b>$class_name</b> nao encontrado";
    }
});
