<?php
declare(strict_types=1);

namespace rark\deathnote\item;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\math\Vector3;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\world\Explosion;

abstract class GrimReaper{
	final private function __construct(){/** NOOP */} //インスタンス生成防止
	/**
	 * reasonで指定された死因idをもとに各関数を呼び出します。
	 * reasonがいずれかの死因idとも一致しない場合は例外を投げます
	 *
	 * @param Player $player
	 * @param integer $reason
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public static function order(Player $player, int $reason):void{
		self::setAdventure($player); //クリエイティブだろうと問答無用で殺したいのでアドベンチャーに変更します
		match($reason){
			DeathReasonIds::HEART_ATTACK => self::heartAttack($player), //$reasonが心臓麻痺のidに一致したらプレイヤーを心臓麻痺（笑）させる処理に移行
			DeathReasonIds::FIRE => self::fire($player),
			DeathReasonIds::FALL => self::fall($player),
			DeathReasonIds::POISON => self::poison($player),
			DeathReasonIds::EXPLODE => self::explode($player),
			default => throw new \InvalidArgumentException('undefined reason '.$reason) //idがどの死因にもマッチしなかったら例外を投げ飛ばす
		};
	}

	/**
	 * プレイヤーのゲームモードをアドベンチャーにします
	 *
	 * @param Player $player
	 * @return void
	 */
	protected static function setAdventure(Player $player):void{
		$player->setGamemode(GameMode::ADVENTURE()); //ゲームモード変更
	}

	/**
	 * プレイヤーを殺害します
	 *
	 * @param Player $player
	 * @return void
	 */
	protected static function heartAttack(Player $player):void{
		$player->kill(); // /kill
	}

	/**
	 * プレイヤーを燃やします
	 *
	 * @param Player $player
	 * @return void
	 */
	protected static function fire(Player $player):void{
		$player->setOnFire(100); //燃やす
	}

	/**
	 * プレイヤーを空の彼方へ...
	 *
	 * @param Player $player
	 * @return void
	 */
	protected static function fall(Player $player):void{
		$player->teleport($player->getPosition()->add(0, 0xff, 0)); //上にテレポート
		$player->setMotion(new Vector3(0, 0xff, 0)); //下にモーション付与 (無くてもいいけど、どうせなら勢いつけたい)
	}

	/**
	 * プレイヤーに致死毒を付与します
	 *
	 * @param Player $player
	 * @return void
	 */
	protected static function poison(Player $player):void{
		$player->getEffects()->add((new EffectInstance(VanillaEffects::FATAL_POISON(), 20*100, 10))); //毒付与
	}

	/**
	 * プレイヤーを爆破します
	 *
	 * @param Player $player
	 * @return void
	 */
	protected static function explode(Player $player):void{
		(new Explosion($player->getPosition(), 2.0))->explodeB(); //プレイヤーのいる地点をブロック破壊なしで爆破
	}
}