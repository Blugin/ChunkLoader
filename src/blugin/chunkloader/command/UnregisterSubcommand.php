<?php

/*
 *
 *  ____  _             _         _____
 * | __ )| |_   _  __ _(_)_ __   |_   _|__  __ _ _ __ ___
 * |  _ \| | | | |/ _` | | '_ \    | |/ _ \/ _` | '_ ` _ \
 * | |_) | | |_| | (_| | | | | |   | |  __/ (_| | | | | | |
 * |____/|_|\__,_|\__, |_|_| |_|   |_|\___|\__,_|_| |_| |_|
 *                |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  Blugin team
 * @link    https://github.com/Blugin
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace blugin\chunkloader\command;

use blugin\chunkloader\ChunkLoader;
use blugin\lib\command\exception\defaults\GenericInvalidNumberException;
use blugin\lib\command\exception\defaults\GenericInvalidWorldException;
use blugin\lib\command\Subcommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class UnregisterSubcommand extends Subcommand{
    /** @return string */
    public function getLabel() : string{
        return "unregister";
    }

    /**
     * @param CommandSender $sender
     * @param string[]      $args = []
     *
     * @return bool
     */
    public function execute(CommandSender $sender, array $args = []) : bool{
        if(isset($args[0])){
            GenericInvalidNumberException::validate($args[0]);
            $chunkX = (int) $args[0];
        }elseif($sender instanceof Player){
            $chunkX = $sender->getPosition()->getX() >> 4;
        }else{
            return false;
        }
        if(isset($args[1])){
            GenericInvalidNumberException::validate($args[1]);
            $chunkZ = (int) $args[1];
        }elseif($sender instanceof Player){
            $chunkZ = $sender->getPosition()->getZ() >> 4;
        }else{
            return false;
        }
        if(isset($args[2])){
            GenericInvalidWorldException::validate($args[2]);
            $world = Server::getInstance()->getWorldManager()->getWorldByName($args[2]);
        }elseif($sender instanceof Player){
            $world = $sender->getWorld();
        }else{
            return false;
        }
        /** @var ChunkLoader $plugin */
        $plugin = $this->getMainCommand()->getOwningPlugin();
        if(!$plugin->unregisterChunk($chunkX, $chunkZ, $world->getFolderName())){
            $this->sendMessage($sender, "failure.notRegistered", [
                (string) $chunkX,
                (string) $chunkZ,
                $world->getFolderName()
            ]);
        }else{
            $this->sendMessage($sender, "success", [
                (string) $chunkX,
                (string) $chunkZ,
                $world->getFolderName()
            ]);
        }
        return true;
    }
}