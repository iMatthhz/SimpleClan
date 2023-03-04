<?php

namespace matheuss\SimpleClan\commands;

class subkick{
	public static function execute($sender, $args, $plugin){
    	if(count($args) < 2){
            $sender->sendMessage('esperado 2 argumentos, recebidos: '.count($args));
            return;
        }
        
        if(!$plugin->inClan($sender)){
            $sender->sendMessage('voce nao esta em um clan');
            return;
        }
        
        $clan = $plugin->getClanByPlayer($sender);
        
        if($clan->getLeader() != $sender->getName()){
            $sender->sendMessage('voce nao e o lider do clan');
            return;
        }
        
        $clan->removeMember($args[1]);
        $sender->sendMessage('membro expulso');
    }
}
?>