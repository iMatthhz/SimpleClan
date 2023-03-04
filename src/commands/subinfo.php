<?php

namespace matheuss\SimpleClan\commands;

class subinfo{
    public static function execute($sender, $plugin){
        if(!$plugin->inClan($sender)){
            $sender->sendMessage('voce nao esta em um clan');
            return;
        }    
        
        $sender->sendMessage($plugin->getClanByPlayer($sender)->getInfo());
    }
}