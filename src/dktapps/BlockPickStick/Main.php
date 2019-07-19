<?php

declare(strict_types=1);

namespace dktapps\BlockPickStick;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase{

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvent(PlayerInteractEvent::class, function(PlayerInteractEvent $e){
			if($e->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK and $e->getItem()->getNamedTag()->hasTag("bpstick")){
				$e->setCancelled();
				$e->getPlayer()->pickBlock($e->getBlock(), false);
			}
		}, EventPriority::NORMAL, $this, false);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
			case "bpstick":
				if($sender instanceof Player){
					$item = VanillaItems::STICK()->setCustomName("Block Picker");
					$item->getNamedTag()->setByte("bpstick", 1);
					if(empty($sender->getInventory()->addItem($item))){
						$sender->sendMessage("Given you a block-picking stick");
					}else{
						$sender->sendMessage(TextFormat::RED . "Your inventory is full");
					}
				}else{
					$sender->sendMessage(TextFormat::RED . "This command can't be used from the console");
				}
				return true;
			default:
				return false;
		}
	}
}
