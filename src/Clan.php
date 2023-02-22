<?php
    
namespace matheuss\SimpleClan;

use pocketmine\utils\Config;
use pocketmine\player\Player;

class Clan {
    
    private string $name;
    private Main $main;
    private int $maxMembersCount = 8;
    
    /**
     * @Param string $name
     * @param Main   $main
     */
    public function __construct(string $name, Main $main){
        $this->name = $name;
        $this->main = $main;
    }
    
    /**
     * @return string $this->name
     */
    public function getName(): string{
        return $this->name;
    }
    
    public function addMember($player){
		if($this->getMembersCount() == $this->maxMembersCount){
            $player->sendMessage('.max_members_count');
            return;
        }
        
        $data = $this->getData();
        $members = $data->getAll()['members'];
        $members[] = $player->getName();
        $data->set('members', $members);
        $data->save();
        $this->main->getPlayers()->set($player->getName(), $this->getName());
        $this->main->getPlayers()->save();
        $this->main->removePlayerInvites($player);
        $player->sendMessage('clan accept');
    }
    
    public function getLeader(){
        return $this->getData()->get('leader');
    }
    
    /**
     * @return int
     */
    public function getMembersCount(): int{
        return count($this->getData()->get('members'));
    }
    
    /**
     * @retrun pmmp/utils/Config #clanname.yml
     */
    public function getData(){
        return new Config($this->main->getPath().$this->getName().'.yml');
    }
    
    public function getInfo(): string{
        $leader = $this->getLeader();
        $members = implode("\n- ", $this->getData()->getAll()['members']);
        $name = $this->getName();
        $membersCount = $this->getMembersCount();
        $maxMembers = $this->maxMembersCount;
        
        return "{$name} {$membersCount}/{$maxMembers}\nLider:{$leader}\nMembros:\n{$members}";
    }
}