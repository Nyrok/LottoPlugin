<?php

declare(strict_types=1);

namespace Nyrok\LottoPlugin;

use JsonException;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use DateTime;
use DateTimeZone;

class Main extends PluginBase{
    private Config $config;
    public function onEnable(): void
    {
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
            "ticket-price" => 100,
            "repeating-time" => 15,
            "messages" => array(
                "success" => array(
                    "win" => "§a{winner} has won lotto for {cashprize}$ and {participants} participants !",
                    "no-winner" => "§aNobody was in lotto.",
                    "repeating" => "§aDraw in {timeleft} minutes with current total sum of {cashprize}$",
                    "ticket-buy" => "§aYou successfully buyed {tickets} tickets for {amount}$",
                    "ticket-info-buyed" => "§aYou have buyed {tickets} tickets for {amount}$",
                    "ticket-info-not-buyed" => "§aYou don't have tickets.",
                    "loto-command-answer" => "§aTimeleft before draw : {timeleft} minutes and current total sum is : {cashprize}$"
                ),
                "errors" => array(
                    "cant-buy-under-one" => "§cYou can't buy {tickets} ticket",
                    "not-enough-money" => "§cYou don't have enough money to buy {tickets} ticket",
                    "didnt-enter-amount" => "§cYou didn't enter an amount of tickets",
                    "usage" => "§cUsage :\n§c/ticket buy <"."amount>\n§c/ticket info"
                )),
            "lotto" => array()));
        $this->getScheduler()->scheduleRepeatingTask(new LottoTask($this, $this->config), 1*20);
        $this->getLogger()->info("Made by @Nyrok10 on Twitter.");
    }
    public function onLoad(): void
    {
        $version = explode(".", $this->getServer()->getApiVersion());
        if($version[0] !== "3"){
            $this->getLogger()->alert("This plugin only works with 3.X.X pocketmine version !");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        if(!$this->getServer()->getPluginManager()->getPlugin("EconomyAPI")){
            $this->getLogger()->alert("You don't have EconomyAPI on this serveur !");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
            "ticket-price" => 100,
            "repeating-time" => 15,
            "messages" => array(
                "success" => array(
                    "win" => "§a{winner} has won lotto for {cashprize}$ and {participants} participants !",
                    "no-winner" => "§aNobody was in lotto.",
                    "repeating" => "§aDraw in {timeleft} minutes with current total sum of {cashprize}$",
                    "ticket-buy" => "§aYou successfully buyed {tickets} tickets for {amount}$",
                    "ticket-info-buyed" => "§aYou have buyed {tickets} tickets for {amount}$",
                    "ticket-info-not-buyed" => "§aYou don't have tickets.",
                    "loto-command-answer" => "§aTimeleft before draw : {timeleft} minutes and current total sum is : {cashprize}$"
                ),
                "errors" => array(
                    "cant-buy-under-one" => "§cYou can't buy {tickets} ticket",
                    "not-enough-money" => "§cYou don't have enough money to buy {tickets} ticket",
                    "didnt-enter-amount" => "§cYou didn't enter an amount of tickets",
                    "usage" => "§cUsage :\n§c/ticket buy <"."amount>\n§c/ticket info"
                )),
            "lotto" => array()));
    }

    /**
     * @throws JsonException
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $this->config->reload();
        if($sender instanceof Player){
            switch($command->getName()){
                case "lotto":
                    $date = new DateTime();
                    $date->setTimezone(new DateTimeZone("Europe/Paris"));
                    $minutes = $date->format("i");
                    $restant = 60 - (int)$minutes;
                    $this->config->save();
                    $addicts = gettype($this->config->get("lotto")) === "array" ? $this->config->get("lotto") : array();
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
                                    $this->config->setNested("lotto.".$sender->getName(), ($this->config->getNested("lotto.".$sender->getName()) ?? 0) + $amount);
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
                            $amount = $this->config->getNested("lotto.".$sender->getName());
                            $message = $this->config->getNested("messages.success.ticket-info-buyed");
                            $message = str_replace("{tickets}",  (string)($amount / $amplifier), $message);
                            $message = str_replace("{amount}", (string)$amount, $message);
                            $info = $this->config->getNested("lotto.".$sender->getName()) ? $message : $this->config->getNested("messages.success.ticket-info-not-buyed");
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
        }
        else {
            $this->getLogger()->alert("Commande exécutable uniquement en jeu !");
        }
        return true;
    }
}
