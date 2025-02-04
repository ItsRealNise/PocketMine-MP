<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\Random;
use pocketmine\world\generator\object\CrimsonFungi;
use pocketmine\world\generator\object\WarpedFungi;
use function mt_rand;

class Fungus extends NetherSprouts {

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($this->getSide(Facing::UP)->getTypeId() === BlockTypeIds::AIR){
			return false;
		}

		if($item instanceof Fertilizer && $this->grow()){
			$item->pop();

			return true;
		}

		return true;
	}

	public function grow() : bool{
		$blockId = $this->getTypeId();
		if ($blockId === BlockTypeIds::CRIMSON_FUNGUS) {
			(new CrimsonFungi())->getBlockTransaction($this->position->world, $this->position->x, $this->position->y, $this->position->z, new Random(mt_rand()));
			return true;
		}

		if ($blockId === BlockTypeIds::WARPED_FUNGUS){
			(new WarpedFungi())->getBlockTransaction($this->position->world, $this->position->x, $this->position->y, $this->position->z, new Random(mt_rand()));
			return true;
		}

		return false;
	}
}
