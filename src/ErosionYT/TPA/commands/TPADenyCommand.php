<?php

namespace ErosionYT\TPA\commands;

use pocketmine\{command\Command,
    command\CommandSender,
    player\Player};

use ErosionYT\TPA\TPA;

class TPADenyCommand extends Command {

    private $owner;

    public function __construct(TPA $owner){
        parent::__construct("tpadeny", $owner->getConfig()->get("tpadenyCommandDescription"), "/tpadeny");
        $this->owner = $owner;
        $this->setPermission("tpa.command");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): bool{
        if(!$this->testPermission($player)){
            return true;
        }

        if($player instanceof Player){
            if($this->owner->hasRequest($player)){
                $this->owner->denyTPARequest($player);
            }else{
                $player->sendMessage("§6» §7You do not have any teleport requests");
            }
        } else {
            $player->sendMessage("§cThis command can only be used in-game");
        }
        return true;
    }
}
