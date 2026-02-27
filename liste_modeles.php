<?php
header('Content-Type: application/json');

$dossier = 'modeles/';
$fichiers = glob($dossier . '*.glb');

$liste = [];
foreach ($fichiers as $fichier) {
  $liste[] = [
    'nom' => basename($fichier, '.glb'),
    'fichier' => $fichier
  ];
}

echo json_encode($liste);
?>