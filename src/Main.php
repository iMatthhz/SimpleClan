<?php

declare(strict_types=1);

namespace matheuss\SimpleClan;

use matheuss\SimpleClan\commands\Commands;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Main extends PluginBase implements Listener {
    
    function onEnable(): void {
        $this->genData();
        $this->getServer()->getCommandMap()->register('clan', new Commands($this));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    function genData() {
        $this->playersData = new Config($this->getDataFolder().'players.yml');
        $this->invitesData = new Config($this->getDataFolder().'invites.yml');
        @mkdir($this->getDataFolder().'clans');
    }
    
    function getPlayers() {
        return $this->playersData;
    }
    
    function getInvites(){
    	return $this->invitesData;
    }
    
    function getPlayerInvites($player){
        return ($this->invitesData->get($player->getName()) == false) ? array() : $this->invitesData->get($player->getName());
    }
    
    function create($name, $tag, $player){
        if($this->inClan($player)) return false;
        if(in_array($name, $this->getAllClans())) return false;
        if(($l = mb_strlen($name)) > 15 or $l < 5) return false;
        if(mb_strlen($tag) != 3) return false;
                
        $clan = new Config($this->getPath().$name.'.yml');
        $clan->setDefaults(['leader' => $player->getName(), 'members' => []]);
        $clan->save();
        $this->playersData->set($player->getName(), $name);
        $this->playersData->save();
        return true;
    }
    
    function delete($player){
        if(!$this->inClan($player)) return false;
        
		$clan = $this->getClanByPlayer($player);
        if($clan->getLeader() != $player->getName()) return false;
        
        $this->playersData->remove($player->getName());
        $this->playersData->save();
        
        @unlink($this->getPath().$clan->getName().'.yml');
        return true;
    }
    
    function getPath(){
        return $this->getDataFolder().'clans/';
    }
    
    function sendInvite($player, $sender){
        if(!$this->inClan($sender)) return false;
        
        $player = $this->getServer()->getPlayerByPrefix($player);
        
        if(!$player) return false;
        
        $clan = $this->getClanByPlayer($sender);
        $invites = $this->invitesData->get($player->getName());
        $invites[] = $clan->getName();
        $this->invitesData->set($player->getName(), $invites);
        $this->invitesData->save();
        return true;
	}
    
    function getClanByPlayer($player){
        if(!$this->inClan($player)) return null;
        return new Clan($this->playersData->get($player->getName()), $this);
    }
    
    function getClanByName($name){
        return new Clan($name, $this);
    }
    
    function acceptInvite($player, $name){
        if(($invites = $this->invitesData->get($player->getName()))){
          if(in_array($name, $invites)){
            $clan = $this->getClanByName($name);
            $clan->addMember($player);
            return true;
          }
        }
        
        return false;
    }
           
    function inClan($player){
        return isset($this->getPlayers()->getAll()[$player->getName()]);
    }
        
    function getAllClans(){
    	foreach(scandir($this->getPath()) as $name){
            $clans[] = str_replace('.yml', '', $name);
        }
        
        return $clans;
    }
    
    function onHit(EntityDamageByEntityEvent $event){
		$damager = $event->getDamager();
        $entity = $event->getEntity();
        
        if(!$entity instanceof Player or !$damager instanceof Player) return;
        
        if(!$this->canHit($damager, $entity)){
            $event->cancel();
        }
    }
    
    function canHit($damager, $player){
        $clan[] = $this->getClanByPlayer($damager);
        $clan[] = $this->getClanByPlayer($player);
        
        if($clan[0] == null or $clan[1] == null) return true;
        if($clan[0]->getName() != $clan[1]->getName()) return true;
        
        return false;
    }
    
    function removePlayerInvites($player){
        $this->invitesData->remove($player->getName());
        $this->invitesData->save();
    }
}
