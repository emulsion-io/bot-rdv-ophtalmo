<?php

// ini_set ('display_errors', 1); 
// ini_set ('display_startup_errors', 1); 
// error_reporting (E_ALL);

require 'Discordbot.php';

$config = parse_ini_file("config.ini", true);

if (class_exists('Discordbot')) {
    $discordBot = new Discordbot($config['app']['discordLink'], $config['app']['discordName']);
} else {
    die('Erreur: La classe Discordbot n’existe pas');
}

$codeMedecin = $config['app']['codeMedecin'];
$numMotif    = $config['app']['numMotif'];

$datedebut = new DateTime();
$datefin = clone $datedebut;
$datefin->modify('+30 days');

$datedebutStr = $datedebut->format('Y/m/d');
$datefinStr = $datefin->format('Y/m/d');

$datedebut_e = urlencode($datedebutStr);
$datefin_e   = urlencode($datefinStr);

$url = "https://www.rdvsms.com/sms/smsApiLoadDispo.php?dateDebG={$datedebut_e}&dateFinG={$datefin_e}&CodeMedecin={$codeMedecin}&NbRdv=&numMotif={$numMotif}";
$json = file_get_contents($url);
$data = json_decode($json, true);

$dateHeureDuJour = $datedebut->format("Y-m-d H:i:s");

$embeds = [];
foreach ($data as $day) {
    if (count($day) > 1) {
        $creneaux = implode("\n", array_slice($day, 1));
        $embeds[] = [
            "title" => "Date du " . $day[0],
            "description" => "Créneaux disponibles: \n**" . $creneaux . "**",
            "color" => 5814783
        ];
    }
}

if (!empty($embeds)) {
    $message = [
        "content" => "OphtalmoBot - Résultat du " . $dateHeureDuJour . "",
        "embeds" => array_merge([
            [
                "title" => "OphtalmoBot - Résultat du " . $datedebut . " au " . $datefin . "",
                "description" => "Recherche des dates disponibles pour vous.",
                "color" => 16751448
            ]
        ], $embeds)
    ];

    $discordBot->send($message);

    $message = [
        "content" => "[Lien de reservation]({$config['app']['lien']})"
    ];

    $discordBot->send($message);
} else {
    $discordBot->send([
        "content" => "OphtalmoBot - Résultat du " . $dateHeureDuJour,
        "embeds" => [
            [
                "title" => "OphtalmoBot - Résultat du " . $datedebut . " au " . $datefin . "",
                "description" => "Aucun créneau disponible trouvé.",
                "color" => 16711853
            ]
        ]
    ]);
}
?>
