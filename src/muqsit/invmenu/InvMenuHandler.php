<?php

declare(strict_types=1);

namespace muqsit\invmenu;

use InvalidArgumentException;
use LogicException;
use muqsit\invmenu\session\PlayerManager;
use muqsit\invmenu\type\InvMenuTypeRegistry;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

final class InvMenuHandler{

	/** @var Plugin $registrant */
	private static $registrant = null;
	/** @var InvMenuTypeRegistry $type_registry */
	private static $type_registry;
	/** @var PlayerManager $player_manager */
	private static $player_manager;

	public static function register(Plugin $plugin) : void{
		if(self::isRegistered()){
			throw new InvalidArgumentException("{$plugin->getName()} attempted to register " . self::class . " twice.");
		}

		self::$registrant = $plugin;
		self::$type_registry = new InvMenuTypeRegistry();
		self::$player_manager = new PlayerManager(self::getRegistrant());
		Server::getInstance()->getPluginManager()->registerEvents(new InvMenuEventHandler(self::getPlayerManager()), $plugin);
	}

	public static function isRegistered() : bool{
		return self::$registrant instanceof Plugin;
	}

	public static function getRegistrant() : Plugin{
		return self::$registrant ?? throw new LogicException("Cannot obtain registrant before registration");
	}

	public static function getTypeRegistry() : InvMenuTypeRegistry{
		return self::$type_registry;
	}

	public static function getPlayerManager() : PlayerManager{
		return self::$player_manager;
	}
}
