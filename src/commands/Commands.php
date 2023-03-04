<?php

namespace matheuss\SimpleClan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class Commands extends Command{
    
    public function __construct($main){
        parent::__construct('clan');
        $this->main = $main;
    }
    
    public function execute(CommandSender $sender, string $label, array $args): void{
        if(isset($args[0])){
            if($args[0] == 'criar'){
                subcreate::execute($sender, $args, $this->main);
                return;
            }
            
            if($args[0] == 'convidar'){
                subinvite::execute($sender, $args, $this->main);
                return;
            }
            
            if($args[0] == 'convites') {
                subinvitesinfo::execute($sender, $this->main);
                return;
            }
            
            if($args[0] == 'deletar'){
                subdelete::execute($sender, $this->main);
                return;
            }
            
            if($args[0] == 'info'){
                subinfo::execute($sender, $this->main);
                return;
            }
            
            if($args[0] == 'sair'){
                subexit::execute($sender, $this->main);
                return;
            }
            
            if($args[0] == 'aceitar'){
                subaccept::execute($sender, $args, $this->main);
                return;
            }
            
            if($args[0] == 'expulsar'){
                subkick::execute($sender, $args, $this->main);
                return;
            }
        }
    }
}