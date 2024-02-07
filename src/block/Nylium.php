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

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\Position;
use function count;
use function mt_rand;

class Nylium extends Opaque{

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			VanillaBlocks::NETHERRACK()->asItem()
		];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick() : void{
		$blockAbove = $this->getSide(Facing::UP);
		if(!$blockAbove->isTransparent() || $blockAbove->getTypeId() === BlockTypeIds::SNOW_LAYER){
			//nylium dies
			BlockEventHelper::spread($this, VanillaBlocks::NETHERRACK(), $this);
		}
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($this->getSide(Facing::UP)->getTypeId() !== BlockTypeIds::AIR){
			return false;
		}

		if($item instanceof Fertilizer){
			$item->pop();
			$this->grow($this->position->getWorld(), $this->position, new Random(mt_rand()), 8, 5);

			return true;
		}

		return false;
	}

	private function grow(ChunkManager $world, Position $pos, Random $random, int $count, int $radius) : void{
		/** @var Block[] $arr */
		$arr = [];

		if($this->getTypeId() === BlockTypeIds::WARPED_NYLIUM){
			$arr = [
				VanillaBlocks::WARPED_ROOTS()
			];
		}

		if($this->getTypeId() === BlockTypeIds::CRIMSON_NYLIUM){
			$arr = [
				VanillaBlocks::CRIMSON_ROOTS()
			];
		}

		$arrC = count($arr) - 1;
		for($c = 0; $c < $count; ++$c){
			$x = $random->nextRange($pos->x - $radius, $pos->x + $radius);
			$z = $random->nextRange($pos->z - $radius, $pos->z + $radius);
			if($world->getBlockAt($x, $pos->y + 1, $z)->getTypeId() === BlockTypeIds::AIR && $world->getBlockAt($x, $pos->y, $z)->getTypeId() === $this->getTypeId()){
				$world->setBlockAt($x, $pos->y + 1, $z, $arr[$random->nextRange(0, $arrC)]);
			}
		}
	}

}