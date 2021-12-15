<?php
declare(strict_types=1);

namespace rark\deathnote\item;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\math\Vector3;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\world\Explosion;

class GrimReaper{
	public static function order(Player $player, int $reason):void{
		self::setGamemode($player);
		match($reason){
			DeathReasonIds::HEART_ATTACK => self::heartAttack($player),
			DeathReasonIds::FIRE => self::fire($player),
			DeathReasonIds::FALL => self::fall($player),
			DeathReasonIds::POISON => self::poison($player),
			DeathReasonIds::EXPLODE => self::explode($player),
			default => throw new \InvalidArgumentException('undefined reason '.$reason)
		};
	}

	protected static function setGamemode(Player $player):void{
		$player->setGamemode(GameMode::ADVENTURE());
	}

	protected static function heartAttack(Player $player):void{
		$player->kill();
	}

	protected static function fire(Player $player):void{
		$player->setOnFire(100);
	}

	protected static function fall(Player $player):void{
		$player->teleport($player->getPosition()->add(0, 0xff, 0));
		$player->setMotion(new Vector3(0, 0xff, 0));
	}

	protected static function poison(Player $player):void{
		$player->getEffects()->add((new EffectInstance(VanillaEffects::FATAL_POISON(), 10, 20*100)));
	}

	protected static function explode(Player $player):void{
		(new Explosion($player->getPosition(), 2.0))->explodeB();
	}
}