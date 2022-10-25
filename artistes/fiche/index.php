<?php
$niveau="../../";
include($niveau . "inc/scripts/config.inc.php");
ini_set('display_errors',1);
$arrayMois= array('janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
//Détection de idSport dans la querystring?
$strIdArtiste = $_GET['id_artiste'];
var_dump( $strIdArtiste);
if(isset($_GET['idStyle']) == true) {
    $strIdStyle = $_GET['id_style'];
}else{
    //Dans le cas ou il n'y a pas de Querystring
    $strIdStyle = 0;
}
$strRequeteArtiste = 'SELECT provenance, site_web_artiste, nom_artiste, id_artiste, description FROM t_artiste';


//Exécution de la requête
$pdosResultat = $pdoConnexion->query($strRequeteArtiste);

//Affichage des erreurs SQL
var_dump($pdoConnexion->errorInfo());

//Extraction de l'enregistrements de la BD
$arrArtistes = array();
for ($intCpt = 0; $ligne = $pdosResultat->fetch(); $intCpt++) {
    $arrArtistes[$intCpt]['nom_artiste'] = $ligne['nom_artiste'];
    $arrArtistes[$intCpt]['id_artiste'] = $ligne['id_artiste'];
    $arrArtistes[$intCpt]['provenance'] = $ligne['provenance'];
    $arrArtistes[$intCpt]['site_web_artiste'] = $ligne['site_web_artiste'];
    $arrArtistes[$intCpt]['description'] = $ligne['description'];

    //sous-requete style
    $strRequeteStyle =  'SELECT nom_style, ti_style_artiste.id_artiste, ti_style_artiste.id_style FROM t_style
		     INNER JOIN ti_style_artiste ON ti_style_artiste.id_style=t_style.id_style
		     WHERE ti_style_artiste.id_artiste='. $ligne['id_artiste'];

    //Initialisation de l'objet PDOStatement et exécution de la requête
    $pdosSousResultat = $pdoConnexion->prepare($strRequeteStyle);
    $pdosSousResultat->execute();

    $ligneStyle = $pdosSousResultat->fetch();
    $strStyles="";
    //Extraction des noms de Sports de la sous requête
    for($intCptStyle=0;$intCptStyle<$pdosSousResultat->rowCount();$intCptStyle++){
        if($strStyles != ""){
            $strStyles = $strStyles . ", ";    //ajout d'une virgule lorsque nécessaire
        }
        $strStyles = $strStyles . $ligneStyle['nom_style'];
        $ligneStyle = $pdosSousResultat->fetch();
    }
    //On libère la sous requête
    $pdosSousResultat->closeCursor();

    //ajout d'un propriété pour afficher les sports
    $arrArtistesStyle[$intCpt]['style_artiste'] = $strStyles;

    //On passe à l'autre participant
    $ligne=$pdosResultat->fetch();
}

//Libération de la requête
$pdosResultat->closeCursor();

$strRequete = 'SELECT ti_evenement.id_artiste, MONTH(date_et_heure) AS mois, YEAR(date_et_heure) AS annee, DAYOFMONTH(date_et_heure) AS jourMois, Time(date_et_heure) AS heure, ti_evenement.id_lieu, nom_lieu 
                FROM ti_evenement INNER JOIN t_lieu
                ON ti_evenement.id_lieu = t_lieu.id_lieu
                WHERE ti_evenement.id_artiste=' . $strIdArtiste . '
                ORDER BY date_et_heure ASC';


//Exécution de la requête
$pdosResultat = $pdoConnexion->query($strRequete);

//Affichage des erreurs SQL
var_dump($pdoConnexion->errorInfo());

//Extraction de l'enregistrements de la BD
$arrLieu = array();
for ($intCpt = 0; $ligne = $pdosResultat->fetch(); $intCpt++) {
    $arrLieu[$intCpt]['id_artiste'] = $ligne['id_artiste'];
    $arrLieu[$intCpt]['mois'] = $ligne['mois'];
    $arrLieu[$intCpt]['annee'] = $ligne['annee'];
    $arrLieu[$intCpt]['jourMois'] = $ligne['jourMois'];
    $arrLieu[$intCpt]['heure'] = $ligne['heure'];
    $arrLieu[$intCpt]['id_lieu'] = $ligne['id_lieu'];
    $arrLieu[$intCpt]['nom_lieu'] = $ligne['nom_lieu'];
};

//Libération de la requête
$pdosResultat->closeCursor();

$strRequeteArtisteSug = 'SELECT DISTINCT t_artiste.id_artiste, nom_artiste FROM t_artiste INNER JOIN
ti_style_artiste ON t_artiste.id_artiste=ti_style_artiste.id_artiste WHERE id_style
IN(SELECT id_style FROM ti_style_artiste WHERE id_artiste=' . $strIdArtiste. ') AND
ti_style_artiste.id_artiste!=' . $strIdArtiste;

$pdoResultatArtistesSug = $pdoConnexion->query($strRequeteArtisteSug);

$arrArtistesSug = array();
for ($intCptArtisteSug = 0; $ligne = $pdoResultatArtistesSug->fetch(); $intCptArtisteSug++) {
    $arrArtistesSug[$intCptArtisteSug]['id_artiste'] = $ligne['id_artiste'];
    $arrArtistesSug[$intCptArtisteSug]['nom_artiste'] = $ligne['nom_artiste'];
};
$pdoResultatArtistesSug->closeCursor();

$arrArtistesChoisis=array();
for ($cpt=0;$cpt<=2;$cpt++){
    if(count($arrArtistesSug)>1) {
        $artisteChoisi=rand(0, count($arrArtistesSug)-1);
        array_push($arrArtistesChoisis, $arrArtistesSug[$artisteChoisi]);
        array_splice($arrArtistesSug, $artisteChoisi, 1);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Fiche de l'artiste</title>
    <?php include($niveau . "inc/fragments/header.inc.html")?>
</head>

<body class="demo">

<img src="<?php $niveau?>/images/<?php $strIdArtiste?>/imageArtiste.jpg">

<h1><?php $arrArtistes[0]['nom_artiste']?></h1>
<ul>
    <li class="provenance">
        <?php echo $arrArtistes[$intCpt]['provenance']?>
    </li>

    <li class="website">
        <?php echo $arrArtistes[$intCpt]['site_web_artiste']?>
    </li>
</ul>
<ul>
    <?php
    for($cptEnr=0;$cptEnr<count($arrLieu);$cptEnr++){?>
    <li>
        <?php
        echo $arrArtistesStyle[$cptEnr]['style_artiste']; }?>
    </li>
    <li>
        <h2><?php echo $arrLieu[$cptEnr]['nom_lieu'];?></h2>
    </li>
    <li>
        <h2><?php echo $arrLieu[$cptEnr]['jourMois'];?> <?php echo $arrayMois[$arrLieu[$cptEnr]['mois']+1];?></h2>
    </li>
    <li>
        <h2>
            <?php echo $arrLieu[$cptEnr]['heure']; ?>
        </h2>
    </li>
</ul>
<li>
    <p><?php echo $arrArtistes[$intCpt]['description']?></p>
</li>

<ul>
    <?php for($cpt=0;$cpt<count($arrArtistesChoisis); $cpt++){?>
        <li>
            <a href="p-fiche-eloise.php?id_artiste=<?php echo $arrArtistesChoisis[$cpt]["id_artiste"];?>"><?php echo $arrArtistesChoisis[$cpt]['nom_artiste'];?></a>
        </li>
    <?php }?>
</ul>

<?php include($niveau . "inc/fragments/footer.inc.html")?>
</body>
</html>