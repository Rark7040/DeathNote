<?php
declare(strict_types=1);

namespace rark\deathnote\item;

use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\WritableBook;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class DeathNote extends WritableBook{
	const NAME = 'Death Note';
	const INTERNAL_NAME = 'death_note';
	const DESCRIPTION = [
		'【How to use】',
		'1. このノートに名前を書かれた人間は死ぬ。',
		'2. 死因を書かなければ全てが心臓麻痺となる。',
		'',
		'【example】',
		'ローレス: 爆死'
	];

	public function __construct(){
		parent::__construct(new ItemIdentifier(ItemIds::WRITABLE_BOOK, 0), self::NAME);
		$this->setCustomName(TextFormat::RED.self::NAME.TextFormat::RESET);
		$this->setLore(self::DESCRIPTION);
	}

	public function use():bool{
		foreach($this->getPages() as $page) self::parseText($page->getText());
		return true;
	}

	protected static function parseText(string $txt):void{
		foreach(explode(PHP_EOL, $txt) as $line){
			$order = explode(':', trim($line));

			if(!isset($order[0])) continue;
			$player = Server::getInstance()->getPlayerExact(trim((string) $order[0]));
			$reason = isset($order[1])? self::getDeathReason(trim((string) $order[1])): DeathReasonIds::HEART_ATTACK;

			if($player === null) continue;
			GrimReaper::order($player, $reason);
		}
	}

	public static function getDeathReason(string $reason, int $fallback = DeathReasonIds::HEART_ATTACK):int{
		return isset(DeathReasonIds::REASON[$reason])? DeathReasonIds::REASON[$reason]: $fallback;
	}
}