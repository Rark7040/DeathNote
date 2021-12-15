<?php
declare(strict_types=1);

namespace rark\deathnote;

use pocketmine\item\ItemFactory;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;
use rark\deathnote\item\DeathNote;

class Loader extends PluginBase{
	protected function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener, $this);
		$this->registerItem();
	}

	protected function registerItem():void{
		$note = new DeathNote;

		/** @var ItemFactory $factory */
		$factory = ItemFactory::getInstance();
		$factory->register($note, true);

		/** @var StringToItemParser $parser */
		$parser = StringToItemParser::getInstance();
		$parser->register(DeathNote::INTERNAL_NAME, fn() => $note);
	}
}

//$ref_func = new \ReflectionMethod(VanillaItems::class, 'register'); //VanillaItems::DEATH_NOTE()
//$ref_func->setAccessible(true);
//$ref_func->invoke(null, 'death_note', $note);