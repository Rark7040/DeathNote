<?php
declare(strict_types=1);

namespace rark\deathnote\item;

interface DeathReasonIds{
	const HEART_ATTACK = 1;
	const FIRE = 2;
	const FALL = 3;
	const POISON = 4;
	const EXPLODE = 5;
	const REASON = [
		'心臓麻痺' => DeathReasonIds::HEART_ATTACK,
		'焼死' => DeathReasonIds::FIRE,
		'落下死' => DeathReasonIds::FALL,
		'毒死' => DeathReasonIds::POISON,
		'爆死' => DeathReasonIds::EXPLODE,
	];
}