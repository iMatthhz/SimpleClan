<?php

declare(strict_types=1);

/** simple memory help :)
getClanByPlayer(Player) -> Clan
removePlayerInvites(Player) -> void
*/

namespace matheuss\SimpleClan;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;

class Main extends PluginBase {
    
    /**
     * @see pmmp/plugin/PluginBase
     */
    public function onEnable(): void {
        $this->genData();
        
        $this->getServer()->getCommandMap()->register('clan', new Commands($this));
    }
    
    private function genData() {
        $path = $this->getDataFolder();
        
        $this->playersData = new Config($path.'players.yml');
        $this->invitesData = new Config($path.'invites.yml');
        @mkdir($path.'clans');
    }
    
    /**
     * @return pmmp/utils/Config #players.yml
     */
    public function getPlayers() {
        return $this->playersData;
    }
    
    public function getInvites(){
    	return $this->invitesData;
    }
    
    public function getPlayerInvites(Player|ConsoleCommandSender $player){
        return $this->getInvites()->get($player->getName());
    }
    
    /**
     * @Param string $name
     * @Param string $tag
     * @Param Player $player
     * 
     * @return bool
     */
    public function create(string $name, string $tag, Player|ConsoleCommandSender $player): bool{
        if(in_array($name, $this->getAllClans())){
            return false;
        }
        
        if($this->isInClan($player)){
            return false;
        }
        
        if(!$this->isName($name)){
            return false;
        }
        
        if(!$this->isTag($tag)){
            return false;
        }
        
        $clan = new Config($this->getPath().$name.'.yml');
        $clan->set('leader', $player->getName());
        $clan->set('members', []);
        $clan->save();
        $this->getPlayers()->set($player->getName(), $name);
        $this->getPlayers()->save();
        return true;
    }
    
    public function delete($player){
        if(!$this->isInClan($player)) return false;
        
		$clan = $this->getClanByPlayer($player);
        if($clan->getLeader() != $player->getName()) return false;
        
        $this->getPlayers()->remove($player->getName());
        $this->getPlayers()->save();
        
        @unlink($this->getPath().$clan->getName().'.yml');
        return true;
    }
    
    /**
     * @Param string $name
     *
     * @return bool
     */
    public function isName(string $name): bool{
        $lenght = mb_strlen($name);
        
        if($lenght > 15){
            return false;
        }
        
        if($lenght < 5){
            return false;
        }
        
        return true;
    }
    
    /**
     * @Param string $tag
     *
     * @return bool
     */
    public function isTag(string $tag): bool{
        $lenght = mb_strlen($tag);
        
        if($lenght > 3){
            return false;
        }
        
        if($lenght < 3){
            return false;
        }
        
        return true;
    }
    
    /**
     * @return string
     */
    public function getPath(): string{
        return $this->getDataFolder().'clans/';
    }
    
    public function sendInvite(string $player, $sender): bool{
        if(!$this->isInClan($sender)) return false;
        
        
        $player = $this->getServer()->getPlayerByPrefix($player);
        
        if(!$player) return false;
        
        $clan = $this->getClanByPlayer($sender);
        $invites = $this->getInvites()->get($player->getName());
        $invites[] = $clan->getName();
        $this->getInvites()->set($player->getName(), $invites);
        $this->getInvites()->save();
        
        return true;
	}
    
    
    /**
     * @Param Player $player
     *
     * @return Clan
     */
    public function getClanByPlayer(Player|ConsoleCommandSender $player){
        $name = $this->getPlayers()->get($player->getName());
        return new Clan($name, $this);
    }
    
    /**
     * @Param string $name
     *
     * @return Clan
     */
    public function getClanByName(string $name){
        return new Clan($name, $this);
    }
    
    /**
     * @Param Player $player
     *
     * @return bool
     */
    public function acceptInvite($player, string $name): bool{
        $invites = $this->getInvites()->get($player->getName());
        
		if(!$invites) return false;
        
        if(!in_array($name, $invites)) return false;
        
        $clan = $this->getClanByName($name);
        $clan->addMember($player);
        
        return true;
    }
    
    /**
     * @Param Player|ConsoleCommandSender $player
     *
     * @return bool
     */
    public function isInClan(Player|ConsoleCommandSender $player): bool{
        $players = $this->getPlayers()->getAll();
        
        if(!isset($players[$player->getName()])){
            return false;
        }
        
        return true;
    }
        
    public function getAllClans(){
    	foreach(scandir($this->getPath()) as $name){
            $clans[] = str_replace('.yml', '', $name);
        }
        
        return $clans;
    }
    
    /**
     * @Param Player $player
     *
     * @return void
     */
    public function removePlayerInvites(Player|ConsoleCommandSender $player): void{
        $this->getInvites()->remove($player->getName());
        $this->getInvites()->save();
        return;
    }
}
