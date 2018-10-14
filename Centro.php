<?php

namespace Obomm;

use muqsit\invmenu\InvMenu;

class Centro extends PluginBase{

    /** @var InvMenu */
    private $menu;

    public function __construct(string $name){
        $this->menu = InvMenu::create(InvMenu::TYPE_CHEST)
            ->readonly()
            ->setName($name)
            ->setListener([$this, "onServerSelectorTransaction"])//you can call class functions this way
            ->onInventoryClose(function(Player $player) : void{
                $player->sendMessage(TextFormat::GREEN."You are being transferred...");
            });
    }

    public function addServerToList(Item $item, string $address, int $port) : void{
        $nbt = $item->getNamedTag();
        $nbt->setByte("Server", $address.":".$port);
        $item->setNamedTagEntry($nbt->getByte("Server"));
        $this->menu->addItem($item);
    }

    public function onServerSelectorTransaction(Player $player, Item $itemClickedOn) : bool{
        $player->transfer(...explode(":", $itemClickedOn->getNamedTag()->getString("Server", "play.onthefallbackserv.er:19132")));
        return true;
    }

    public function sendTo(Player $player) : void{
        $this->menu->send($player);
    }
}

$selector = new Example("Server Selector");
$selector->addServerToList(Item::get(Item::DIAMOND_PICKAXE), "play.onmyserverplea.se", 19132);
$selector->addServerToList(Item::get(Item::IRON), "play.onmyserverplea.se", 19133);

/** @var Player $player */
$selector->sendTo($player);
