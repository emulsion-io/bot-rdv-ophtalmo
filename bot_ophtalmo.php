<?php

/**
 * Bot RDV Ophtalmo
 * 
 * @autor: Simonet Fabrice | Emulsion.io
 * 
 */

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

$dateHeureDuJour = $datedebut->format("d/m/Y H:i:s");

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
                "title" => "OphtalmoBot - Résultat du " . $datedebutStr . " au " . $datefinStr . "",
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
                "title" => "OphtalmoBot - Résultat du " . $datedebutStr . " au " . $datefinStr . "",
                "description" => "Aucun créneau disponible trouvé.",
                "color" => 16711853
            ]
        ]
    ]);
}
?>
