<?php

namespace ErosionYT\TPA\commands;

use pocketmine\{command\Command,
    command\CommandSender,
    player\Player};

use ErosionYT\TPA\TPA;

class TPAcceptCommand extends Command {

    private $owner;

    public function __construct(TPA $owner){
        parent::__construct("tpaccept", $owner->getConfig()->get("tpacceptCommandDescription"), "/tpaccept");
        $this->owner = $owner;
        $this->setPermission("tpa.command");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): bool{
        if(!$this->testPermission($player)){
            return true;
        }

        if($player instanceof Player){
            if($this->owner->hasRequest($player)){
                if($this->owner->teleporteeStillOnline($player)){
                    if(isset($this->owner->tpaReq[$player->getName()]["teleport"])){
                        $teleportee = $this->owner->getTeleportee($player);
                        if($teleportee !== null){
                            $player->teleport($teleportee->getPosition());
                            $this->owner->destroyRequest($player);

                            $player->sendMessage("§6» §7You have teleported successfully");
                            $teleportee->sendMessage("§6» §7You have teleported successfully");
                        } else {
                            $player->sendMessage("§cTeleportee cannot be found");
                        }
                    }else{
                        $teleportee = $this->owner->getTeleportee($player);
                        if($teleportee !== null){
                            $teleportee->teleport($player->getPosition());
                            $this->owner->destroyRequest($player);

                            $player->sendMessage("§6» §7You have teleported successfully");
                            $teleportee->sendMessage("§6» §7You have teleported successfully");
                        } else {
                            $player->sendMessage("§cTeleportee cannot be found");
                        }
                    }
                } else {
                    $player->sendMessage("§cTeleportee is no longer online");
                }
            }else{
                $player->sendMessage("§6» §7You do not have any teleport requests");
            }
        } else {
            $player->sendMessage("§cThis command can only be used in-game");
        }
        return true;
    }
}
