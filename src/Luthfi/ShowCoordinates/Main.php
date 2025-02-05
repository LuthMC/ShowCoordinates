<?php

namespace Luthfi\ShowCoordinates;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\player\Player;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->startCoordinateDisplay();
    }

    private function startCoordinateDisplay(): void {
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function(): void {
            foreach ($this->getServer()->getOnlinePlayers() as $player) {
                if ($player->isOnline()) {
                    $this->sendCoordinates($player);
                }
            }
        }), 20);
    }

    /**
     * Send player current coordinates as an action bar message.
     *
     * @param Player $player
     */
    private function sendCoordinates(Player $player): void {
        $position = $player->getPosition();
        $coords = "Position: " . round($position->getX(), 1) . ", " . round($position->getY(), 1) . ", " . round($position->getZ(), 1);
        $player->sendActionBarMessage($coords);
    }
}
