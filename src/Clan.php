<?php
    
namespace matheuss\SimpleClan;

use pocketmine\utils\Config;
use pocketmine\player\Player;

class Clan {
    
    function __construct($name, $main){
        $this->name = $name;
        $this->main = $main;
    }
    
    function getName(){
        return $this->name;
    }
    
    function addMember($player){
		if($this->getMembersCount() == 8){
            $player->sendMessage('.max_members_count');
            return;
        }
        
        $data = $this->getData();
        $members = $data->getAll()['members'];
        $members[] = $player->getName();
        $data->set('members', $members);
        $data->save();
        $this->main->playersData->set($player->getName(), $this->getName());
        $this->main->playersData->save();
        $this->main->removePlayerInvites($player);
        $player->sendMessage('clan accept');
    }
    
    function getLeader(){
        return $this->getData()->get('leader');
    }
    
    function getMembersCount(){
        return count($this->getData()->get('members'));
    }
    
    function getData(){
        return new Config($this->main->getPath().$this->getName().'.yml');
    }
    
    function removeMember($player){
        $data = $this->getData();
        $members = $data->getAll()['members'];
        unset($members[$player]);
        
        $data->set('members', $members);
        $data->save();
        $this->main->playersData->remove($player->getName());
        $this->main->playersData->save();
    }
    
    function getInfo(){
        $leader = $this->getLeader();
        $members = implode("\n- ", $this->getData()->getAll()['members']);
        $name = $this->getName();
        $membersCount = $this->getMembersCount();
        $maxMembers = 8;
        
        return "{$name} {$membersCount}/{$maxMembers}\nLider:{$leader}\nMembros:\n{$members}";
    }
}