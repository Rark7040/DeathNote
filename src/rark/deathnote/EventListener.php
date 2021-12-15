<?php
declare(strict_types=1);

namespace rark\deathnote;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\Server;
use rark\deathnote\item\DeathNote;

class EventListener implements Listener{
	public function onDropItem(PlayerDropItemEvent $ev):void{
		$item = $ev->getItem();
		$player = $ev->getPlayer();

		if(!$item instanceof DeathNote) return;
		if(!Server::getInstance()->isOp($player->getName())) return;
		$item->use();
		$ev->cancel();
	}
}