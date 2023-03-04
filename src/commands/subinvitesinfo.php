<?php

namespace matheuss\SimpleClan\commands;

class subinvitesinfo {
	public static function execute($sender, $plugin){
    	if($plugin->inClan($sender)){
        	$sender->sendMessage('voce esta em um clan');
            return;
        }
        
        $sender->sendMessage(implode("\n", $plugin->getPlayerInvites($sender)));
    }
}
?>