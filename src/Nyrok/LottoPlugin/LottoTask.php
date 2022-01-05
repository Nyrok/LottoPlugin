<?php

namespace Nyrok\LottoPlugin;

use JsonException;
use onebone\economyapi\EconomyAPI;
use pocketmine\scheduler\Task;
use DateTime;
use DateTimeZone;
use pocketmine\utils\Config;

class LottoTask extends Task
{
    private Main $main;
    private Config $config;

    public function __construct(Main $main, Config $config)
    {
        $this->main = $main;
        $this->config = $config;
    }

    /**
     * @throws JsonException
     */
    public function onRun():void
    {
        $this->config->reload();
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone("Europe/Paris"));
        $minutes = $date->format("i");
        $seconds = $date->format("s");
        $this->config->save();
        $repeating = $this->config->get("repeating-time") ?? 15;
        $repeating = $repeating >= 60 || $repeating <= 1 ? 15 : $repeating;
        if(gettype($minutes / $repeating) === "integer" && $seconds == 0){
            $restant = 60 - (int)$minutes === 60 ? 0 : 60 - (int)$minutes;
            $addicts = gettype($this->config->get("lotto")) === "array" ? $this->config->get("lotto") : array() ;
            $cashprize = 0;
            foreach ($addicts as $amount){
                $cashprize = $cashprize + $amount;
            }
            $message = $this->config->getNested("messages.success.repeating");
            $message = str_replace("{timeleft}", (string)$restant, $message);
            $message = str_replace("{cashprize}", (string)$cashprize, $message);
            $this->main->getServer()->broadcastMessage($message);
        }
        if($minutes == 0 && $seconds == 0){
            $this->config->save();
            $is_someone = gettype($this->config->get("lotto")) === "array" ? $this->config->get("lotto") : null;
            $is_someone = $is_someone === [] && gettype($is_someone) === "array" ? array_push($is_someone, array("Nobody")) : $is_someone;
            $addicts = $is_someone !== null ? $this->config->get("lotto") : array();
            $cashprize = 0;
            foreach ($addicts as $amount){
                $cashprize = $cashprize + (int)$amount;
            }
            $winner = count($addicts) > 0 ? array_rand($addicts) : "Nobody";
            $message = $this->config->getNested("messages.success.win");
            $message = str_replace("{winner}", (string)$winner, $message);
            $message = str_replace("{participants}", (string)count($addicts), $message);
            $message = str_replace("{cashprize}", (string)$cashprize, $message);
            $win = $winner !== "Nobody" ? $message : $this->config->getNested("messages.success.no-winner");
            $this->config->remove("lotto");
            $this->config->save();
            EconomyAPI::getInstance()->addMoney($winner, $cashprize);
            $this->main->getServer()->broadcastMessage($win);
        }
    }
}