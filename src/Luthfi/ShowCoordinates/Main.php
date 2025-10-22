<?php

namespace Luthfi\ShowCoordinates;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase {

    private array $toggledPlayers = [];

    public function onEnable(): void {
        $this->startCoordinateDisplay();
    }

    private function startCoordinateDisplay(): void {
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function(): void {
            foreach ($this->getServer()->getOnlinePlayers() as $player) {
                if ($this->isCoordinatesEnabled($player)) {
                    $this->sendCoordinates($player);
                }
            }
        }), 20);
    }

    private function isCoordinatesEnabled(Player $player): bool {
        $name = $player->getName();
        return $this->toggledPlayers[$name] ?? true;
    }

    private function toggleCoordinates(Player $player): bool {
        $name = $player->getName();
        $currentState = $this->isCoordinatesEnabled($player);
        $newState = !$currentState;
        $this->toggledPlayers[$name] = $newState;
        return $newState;
    }

    private function sendCoordinates(Player $player): void {
        $position = $player->getPosition();
        $coords = "Position: " . round($position->getX(), 1) . ", " . round($position->getY(), 1) . ", " . round($position->getZ(), 1);
        $player->sendActionBarMessage($coords);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can only be used in-game.");
            return true;
        }

        $newState = $this->toggleCoordinates($sender);
        
        if ($newState) {
            $sender->sendMessage("§aCoordinates enabled!");
        } else {
            $sender->sendMessage("§cCoordinates disabled!");
        }

        return true;
    }
}
