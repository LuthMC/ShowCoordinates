<?php

namespace Luthfi\ShowCoordinates;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->startCoordinateDisplay($player);
    }

    private function startCoordinateDisplay(Player $player): void {
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function() use ($player): void {
            if ($player->isOnline()) {
                $position = $player->getPosition();
                $coords = "Position: " . round($position->getX(), 1) . ", " . round($position->getY(), 1) . ", " . round($position->getZ(), 1);
                $player->sendActionBarMessage($coords);
            }
        }), 20);
    }
}
