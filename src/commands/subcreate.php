<?php

namespace matheuss\SimpleClan\commands;

class subcreate {    
    public static function execute($sender, $args, $plugin){
        if(count($args) < 3){
        	$sender->sendMessage('esperado 3 argumentos, recebidos: '. count($args));
            return;
        }
        
        if(!$plugin->create($args[1], $args[2], $sender)){
        	$sender->sendMessage('nao foi possivel criar o clan');
            return;
        }
        
        $sender->sendMessage('clan criado');
    }
}
?>