<?php

namespace matheuss\SimpleClan\commands;

class subdelete{
    public static function execute($sender, $plugin){
        if(!$plugin->inClan($sender)){
            $sender->sendMessage('voce nao esta em um clan');
            return;
        }
        
        if(!$plugin->delete($sender)){
            return;
        }
        
        $sender->sendMessage('clan deletado');
    }
}