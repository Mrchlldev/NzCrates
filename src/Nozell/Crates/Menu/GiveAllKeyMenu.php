<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use pocketmine\Server;
use Nozell\Crates\Main;
use Nozell\Crates\Meetings\MeetingManager;
use Nozell\Crates\libs\FormAPI\CustomForm;

final class GiveAllKeyMenu extends CustomForm {

    private array $keyTypes;

    public function __construct(Player $player) {
        parent::__construct(null);

        $this->keyTypes = ["mage", "ice", "ender", "magma", "pegasus"];

        $this->setTitle("Crates");
        $this->addDropdown("Select crates:", $this->keyTypes);
        $this->addInput("Amount:", "Numeric only!");
        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null || !isset($this->keyTypes[$data[0]]) || $data[1] === '' || $data[1] <= 0 || !ctype_digit($data[1])) {
            $player->sendMessage("§cInvalid data!");
            return;
        }

        $keyType = $this->keyTypes[$data[0]];
        $amount = (int)$data[1];

        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $meeting = MeetingManager::getInstance()->getMeeting($onlinePlayer)->getCratesData();

            switch ($keyType) {
                case "mage":
                    $meeting->addKeyMage($amount);
                    break;
                case "ice":
                    $meeting->addKeyIce($amount);
                    break;
                case "ender":
                    $meeting->addKeyEnder($amount);
                    break;
                case "magma":
                    $meeting->addKeyMagma($amount);
                    break;
                case "pegasus":
                    $meeting->addKeyPegasus($amount);
                    break;
                default:
                    $player->sendMessage("§cUnknown key type.");
                    return;
            }

            $onlinePlayer->sendMessage("§cHello! You received a {$amount}x {$keyType}");
        }

        $player->sendMessage("§aSuccesfully given all player {$keyType} with {$amount}x amount");
    }
}
