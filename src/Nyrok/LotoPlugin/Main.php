<?php

declare(strict_types=1);

namespace Nyrok\LotoPlugin;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use DateTime;
use DateTimeZone;

class Main extends PluginBase{
    private Config $config;
    public function onEnable()
    {
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
            "ticket-price" => 100,
            "repeating-time" => 15,
            "messages" => array(
                "success" => array(
                    "win" => "§a{winner} a gagné le loto avec un montant de {cashprize}$ pour {participants} participants !",
                    "no-winner" => "§aPersonne n'a participé au loto",
                    "repeating" => "§aTirage au sort dans {timeleft} minutes avec une somme totale de {cashprize}$",
                    "ticket-buy" => "§aVous avez bien acheté {tickets} tickets pour {amount}$",
                    "ticket-info-buyed" => "§aVous avez {tickets} tickets pour {amount}$",
                    "ticket-info-not-buyed" => "§aVous n'avez pas de ticket.",
                    "loto-command-answer" => "§aIl reste {timeleft} minutes et la somme totale est de {cashprize}$"
                ),
                "errors" => array(
                    "cant-buy-under-one" => "§cVous ne pouvez pas acheter {tickets} ticket",
                    "not-enough-money" => "§cVous n'avez pas assez d'argent pour acheter {tickets} ticket",
                    "didnt-enter-amount" => "§cVous n'avez pas d'entré un montant de ticket à acheter",
                    "usage" => "§cUsage :\n§c/ticket buy <"."amount>\n§c/ticket info"
                )),
            "loto" => array()));
        $this->getScheduler()->scheduleRepeatingTask(new LotoTask($this, $this->config), 1*20);
        $this->getLogger()->info("Made by @Nyrok10 on Twitter.");
    }
    public function onLoad()
    {
        if(!$this->getServer()->getPluginManager()->getPlugin("EconomyAPI")){
            $this->getLogger()->alert("Vous n'avez pas EconomyAPI sur le serveur !");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
            "ticket-price" => 100,
            "repeating-time" => 15,
            "messages" => array(
                "success" => array(
                    "win" => "§a{winner} a gagné le loto avec un montant de {cashprize}$ pour {participants} participants !",
                    "no-winner" => "§aPersonne n'a participé au loto",
                    "repeating" => "§aTirage au sort dans {timeleft} minutes avec une somme totale de {cashprize}$",
                    "ticket-buy" => "§aVous avez bien acheté {tickets} tickets pour {amount}$",
                    "ticket-info-buyed" => "§aVous avez {tickets} tickets pour {amount}$",
                    "ticket-info-not-buyed" => "§aVous n'avez pas de ticket.",
                    "loto-command-answer" => "§aIl reste {timeleft} minutes et la somme totale est de {cashprize}$"
                ),
                "errors" => array(
                    "cant-buy-under-one" => "§cVous ne pouvez pas acheter {tickets} ticket",
                    "not-enough-money" => "§cVous n'avez pas assez d'argent pour acheter {tickets} ticket",
                    "didnt-enter-amount" => "§cVous n'avez pas d'entré un montant de ticket à acheter",
                    "usage" => "§cUsage :\n§c/ticket buy <"."amount>\n§c/ticket info"
                )),
            "loto" => array()));
    }
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $this->config->reload();
        switch($command->getName()){
            case "loto":
                $date = new DateTime();
                $date->setTimezone(new DateTimeZone("Europe/Paris"));
                $minutes = $date->format("i");
                $restant = 60 - (int)$minutes;
                $this->config->save();
                $addicts = gettype($this->config->get("loto")) === "array" ? $this->config->get("loto") : array();
                $cashprize = 0;
                foreach ($addicts as $amount){
                    $cashprize = $cashprize + $amount;
                }
                $message = $this->config->getNested("messages.success.loto-command-answer");
                $message = str_replace("{cashprize}", "$cashprize", $message);
                $message = str_replace("{timeleft}", "$restant", $message);
                $sender->sendMessage($message);
                break;
            case "ticket":
                $this->config->save();
                $amplifier = $this->config->get("ticket-price") ?? 100;
                if(isset($args[0])){
                    if($args[0] === "buy"){
                        if(isset($args[1])){
                            $amount = $args[1];
                            $amount = (int)$amount*$amplifier;
                            if(EconomyAPI::getInstance()->myMoney($sender->getName()) >= $amount && $amount > 0){
                                EconomyAPI::getInstance()->reduceMoney($sender->getName(), $amount);
                                $this->config->save();
                                $this->config->setNested("loto.".$sender->getName(), ($this->config->getNested("loto.".$sender->getName()) ?? 0) + $amount);
                                $this->config->save();
                                $message = $this->config->getNested("messages.success.ticket-buy");
                                $message = str_replace("{amount}", "$amount", $message);
                                $message = str_replace("{tickets}", (string)($amount/$amplifier), $message);
                                $sender->sendMessage($message);
                            }
                            else if($amount <= 0){
                                $message = $this->config->getNested("messages.errors.cant-buy-under-one");
                                $message = str_replace("{tickets}", (string)($amount/$amplifier), $message);
                                $sender->sendMessage($message);
                            }
                            else {
                                $message = $this->config->getNested("messages.errors.not-enough-money");
                                $message = str_replace("{tickets}", (string)($amount/$amplifier), $message);
                                $sender->sendMessage($message);
                            }
                        }
                        else {
                            $message = $this->config->getNested("messages.errors.didnt-enter-amount");
                            $sender->sendMessage($message);
                        }
                    }
                    else if($args[0] === "info") {
                        $this->config->save();
                        $amount = $this->config->getNested("loto.".$sender->getName());
                        $message = $this->config->getNested("messages.success.ticket-info-buyed");
                        $message = str_replace("{tickets}",  (string)($amount / $amplifier), $message);
                        $message = str_replace("{amount}", (string)$amount, $message);
                        $info = $this->config->getNested("loto.".$sender->getName()) ? $message : $this->config->getNested("messages.success.ticket-info-not-buyed");
                        $sender->sendMessage($info);

                    }
                    else {
                        $message = $this->config->getNested("messages.errors.usage");
                        $sender->sendMessage($message);
                    }
                }
                else {
                    $message = $this->config->getNested("messages.errors.usage");
                    $sender->sendMessage($message);
                }
        }
        return true;
    }
}
