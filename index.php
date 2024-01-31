<?php

/**
 * Bot RDV
 * 
 * @autor: Simonet Fabrice | Emulsion.io
 * 
 */

ini_set ('display_errors', 1); 
ini_set ('display_startup_errors', 1); 
error_reporting (E_ALL);

require 'Discordbot.php';

$config = parse_ini_file("config.ini", true);

if (class_exists('Discordbot')) {
    $discordBot = new Discordbot($config['app']['discordLink'], $config['app']['discordName']);
} else {
    die('Erreur: La classe Discordbot n’existe pas');
}

require 'bot_ophtalmo.php';
