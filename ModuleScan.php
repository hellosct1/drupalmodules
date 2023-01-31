<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/ TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<?php
date_default_timezone_set('Europe/Paris');
header( 'content-type: text/html; charset=utf-8' );

$database = 'database';
$hote = 'localhost';
$db_user = 'user';
$db_mdp = 'password';

$titre = 'Liste des Modules Drupal du site -->'.$database;

$req_commune = 'SELECT * FROM system WHERE type="module" ORDER BY ';

if (isset($_GET['ordre'])){
    if ($_GET['ordre']=='status'){
        $requete = $req_commune.'!status, name';
    }
    if ($_GET['ordre']=='chemin'){
        $requete = 'SELECT * FROM system  ORDER BY filename';
    }
} else {
    $requete = $req_commune.' name';
}

echo $requete;

?>
<head>
    <title><?php echo $titre; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<style type="text/css">
    table { border: solid 2px; }
    tr { border: solid 2px; }
    /**td { border: solid 2px; }**/
    .centre { text-align: center; }
    .titre_tab{ font-style: bold ; ;}
    a { text-decoration: none; color: blue }
    a:visited { text-decoration: none; color: blue }
    a:hover { text-decoration: none; color: red; }
</style>
<?php
#### connection à la base de données
try
{    // On se connecte à MySQL
    $bdd = new PDO('mysql:host='.$hote.';dbname='.$database, $db_user, $db_mdp);
}
catch(Exception $e)
{    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur de connection MySQL : '.$e->getMessage());
}

$reponse = $bdd->query($requete);
echo '<h5>'.$titre.'</h5>';
?>
<table>
    <tr>
        <td class="titre_tab">Nbr</td>
        <td class="titre_tab"><a href="ModuleScan.php">Nom du module</a></td>
        <td class="titre_tab" align="center"><a href="ModuleScan.php?ordre=status">Status</a></td>
        <td class="titre_tab" align="center"><a href="ModuleScan.php?ordre=chemin">Chemin</a></td>
    </tr>
    <tr>

<?php
$i = 1;
while ($donnees = $reponse->fetch())
{
    if ($donnees['status']=='1'){ //compte les modules actifs
        $affichage .= '<td>'.$i.'</td>';
    }else{
        $affichage .= '<td></td>';
    }
    $affichage .= '<td>'.$donnees['name'].'</td>';
    $affichage .= '<td class="centre">'.$donnees['status'].'</td>';
    $affichage .= '<td>'.$donnees['filename'].'</td>';
    $affichage .= '</tr>';
    if ($donnees['status']=='1'){
        $i++;
    }
}

$reponse->closeCursor();
$affichage .= '</table>';

## Affichage
echo $affichage;
/*
echo '<pre>';
echo '</pre>';
*/
?>
