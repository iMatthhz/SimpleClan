<?php

namespace matheuss\SimpleClan\commands;

class subexit {
    public static function execute($sender, $plugin){
        if(!$plugin->inClan($sender)){
        	$sender->sendMessage('voce nao esta em um clan');
            return;
        }
                
        if($plugin->getClanByPlayer($sender)->getLeader() == $sender->getName()){
        	$sender->sendMessage('voce nao pode sair, pois voce e lider');
            return;
        }
    }
}