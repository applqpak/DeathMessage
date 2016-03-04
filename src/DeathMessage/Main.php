<?php

  namespace DeathMessage;

  use pocketmine\plugin\PluginBase;
  use pocketmine\event\Listener;
  use pocketmine\utils\TextFormat as TF;
  use pocketmine\utils\Config;
  use pocketmine\Player;
  use pocketmine\event\player\PlayerDeathEvent;
  use pocketmine\event\entity\EntityDamageByEntityEvent;
  use pocketmine\command\Command;
  use pocketmine\command\CommandSender;

  class Main extends PluginBase implements Listener {

    public function dataPath() {

      return $this->getDataFolder();

    }

    public function onEnable() {

      $this->getServer()->getPluginManager()->registerEvents($this, $this);

      @mkdir($this->dataPath());

      $this->cfg = new Config($this->dataPath() . "config.yml", Config::YAML, array("death_message" => "You died! If you are glitching please relog."));

    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {

      if(strtolower($cmd->getName()) === "deathmessage") {

        if(!(isset($args[0]))) {

          $sender->sendMessage(TF::RED . "Error: not enough args. Usage: /deathmessage < message >");

          return true;

        } else {

          $new_death_message = implode(" ", $args);

          $this->cfg->set("death_message", $new_death_message);

          $this->cfg->save();

          $sender->sendMessage(TF::GREEN . "Successfully set new Death Message.");

          return true;

        }

      }

    }

    public function onDeath(PlayerDeathEvent $event) {

      $player = $event->getEntity();

      $death_cause = $event->getEntity()->getLastDamageCuase();

      if($death_cause instanceof EntityDamageByEntityEvent) {

        $killer = $event->getDamager();

        if($killer instanceof Player) {

          $player->sendMessage($this->cfg->get("death_message"));

        }

      }

    }

  }

?>
