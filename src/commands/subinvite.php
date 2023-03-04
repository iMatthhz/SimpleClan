<?php

namespace matheuss\SimpleClan\commands;

class subinvite {
	public static function execute($sender, $args, $plugin){
    	if(count($args) < 2){
        	$sender->sendMessage('esperado 2 argumentos, recebidos: '.count($args));
            return;
        }
        
        $plugin->sendInvite($args[1], $sender);
    }
}
?>