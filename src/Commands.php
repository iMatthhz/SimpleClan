<?php

namespace matheuss\SimpleClan;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Commands extends Command{
    
    private Main $main;
    
    public function __construct(Main $main){
        parent::__construct('clan');
        $this->main = $main;
    }
    
    
    public function execute(CommandSender $sender, string $label, array $args): void{
        if(isset($args[0])){
            if($args[0] == 'criar'){
                if(!isset($args[1])) return;
                if(!isset($args[2])) return;
                
                if(!$this->main->create($args[1], $args[2], $sender)) {
                    $sender->sendMessage('Não foi possivel criar.');
                    return;
                }
                
                $sender->sendMessage('Clan criado com sucesso.');
            }
            
            if($args[0] == 'convidar'){
                if(!isset($args[1])) return;
                
                $this->main->sendInvite($args[1], $sender);
                return;
            }
            
            if($args[0] == 'convites') {
                $i = $this->main->getPlayerInvites($sender);
                
                if(!$i) return;
                
                $sender->sendMessage(implode(',  ', $i));
                return;
            }
            
            if($args[0] == 'deletar'){
                if($this->main->delete($sender)){
                    $sender->sendMessage('clan deletado');
                    return;
                }
                
                return;
            }
            
            if($args[0] == 'info'){
                if(!$this->main->isInClan($sender)) return;
                
                $sender->sendMessage($this->main->getClanByPlayer($sender)->getInfo());
            }
            
            if($args[0] == 'aceitar'){
                if(!isset($args[1])) return;
                
                if($this->main->acceptInvite($sender, $args[1])){
                    $sender->sendMessage('clan aceito');
                    return;
                }
                
                return;
            }
        }
        
        return;
    }
}