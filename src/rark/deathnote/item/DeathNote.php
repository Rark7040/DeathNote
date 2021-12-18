<?php
declare(strict_types=1);

namespace rark\deathnote\item;

use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\WritableBook;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class DeathNote extends WritableBook{
	const NAME = TextFormat::RED.'Death Note'.TextFormat::RESET; //プレイヤーに見せる名前
	const INTERNAL_NAME = 'death_note'; //pmmp側に登録するときに使用する
	const DESCRIPTION = [
		'【How to use】',
		'1. このノートに名前を書かれた人間は死ぬ。',
		'2. 死因を書かなければ全てが心臓麻痺となる。',
		'',
		'【Example】',
		'ローレス: 爆死'
	];

	public function __construct(){
		parent::__construct(new ItemIdentifier(ItemIds::WRITABLE_BOOK, 0), self::INTERNAL_NAME);
		$this->setCustomName(self::NAME);
		$this->setLore(self::DESCRIPTION);
	}

	/**
	 * デスノートが使用されたときに呼び出されます。
	 * 自身の全てのページを読み取り、書き込まれた内容をparseText関数に渡します
	 *
	 * @return void
	 */
	public function use():void{
		foreach($this->getPages() as $page) self::parseText($page->getText());
	}

	/**
	 * 自分に書き込まれた内容を翻訳し、死神くんに渡します
	 *
	 * @param string $txt
	 * @return void
	 */
	protected static function parseText(string $txt):void{
		foreach(explode(PHP_EOL, $txt) as $line){
			$order = explode(':', trim($line)); // ':'で区切られた文章を配列にして渡す

			if(!isset($order[0])) continue; //内容が空だったら次のラインに移動
			$player = Server::getInstance()->getPlayerExact((string) $order[0]); //書き込まれた名前からPlayer Bを取得する
			$reason = isset($order[1])? self::getDeathReason((string) $order[1]): DeathReasonIds::HEART_ATTACK; //もし死因が記入されていた場合はその死因IDを取得する

			if($player === null) continue; //プレイヤーが取得できない・プレイヤーの名前が不正だったら次のラインに移動
			GrimReaper::order($player, $reason); //死神くんにお願いする
		}
	}

	/**
	 * DeathReasonIdsを参照し、文字列から死因IDを取得します。
	 * もし、不正な文字列だった場合はFallBackに指定されたIDを返します。
	 *
	 * @param string $reason
	 * @param int $fallback
	 * @return integer
	 */
	public static function getDeathReason(string $reason, int $fallback = DeathReasonIds::HEART_ATTACK):int{
		return isset(DeathReasonIds::REASON[$reason])? DeathReasonIds::REASON[$reason]: $fallback;
	}
}