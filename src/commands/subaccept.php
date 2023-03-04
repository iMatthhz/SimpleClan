<?php

namespace matheuss\SimpleClan\commands;

class subaccept {
    public static function execute($sender, $args, $plugin){
        if($plugin->inClan($sender)){
            $sender->sendMessage('voce ja esta em um clan');
            return;
        }

        if(count($args) < 2){
            $sender->sendMessage('esperado 2 argumentos, recebidos: '.count($args));
            return;
        }

        if($plugin->acceptInvite($sender, $args[1])){
            return;
        }
    }
}