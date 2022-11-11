<?php
$niveauFiche="../../";
include($niveauFiche . "inc/scripts/config.inc.php");
ini_set('display_errors',1);
//Détection de idSport dans la querystring?
$strIdArtiste = $_GET['id_artiste'];
var_dump( $strIdArtiste);

$strRequeteArtiste = 'SELECT provenance, site_web_artiste, nom_artiste, id_artiste, description FROM t_artiste WHERE id_artiste='.$strIdArtiste;


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

$strRequete = 'SELECT ti_evenement.id_artiste, DATE(date_et_heure) AS date_et_heure, HOUR(date_et_heure) AS heure, MINUTE(date_et_heure) AS minutes, ti_evenement.id_lieu, nom_lieu 
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
    $arrLieu[$intCpt]['heure'] = $ligne['heure'];
    $arrLieu[$intCpt]['date_et_heure'] = $ligne['date_et_heure'];
    $arrLieu[$intCpt]['minute'] = $ligne['minutes'];
    $arrLieu[$intCpt]['id_lieu'] = $ligne['id_lieu'];
    $arrLieu[$intCpt]['nom_lieu'] = $ligne['nom_lieu'];

    if ($arrLieu[$intCpt]['minute'] == '0') {
        $arrLieu[$intCpt]['minute'] = '00';
    }
};

//Libération de la requête
$pdosResultat->closeCursor();

$strRequeteArtisteSug = 'SELECT DISTINCT t_artiste.id_artiste, t_artiste.provenance, nom_artiste FROM t_artiste INNER JOIN
ti_style_artiste ON t_artiste.id_artiste=ti_style_artiste.id_artiste WHERE id_style
IN(SELECT id_style FROM ti_style_artiste WHERE id_artiste=' . $strIdArtiste. ') AND
ti_style_artiste.id_artiste!=' . $strIdArtiste;

$pdoResultatArtistesSug = $pdoConnexion->query($strRequeteArtisteSug);

$arrArtistesSug = array();
for ($intCptArtisteSug = 0; $ligne = $pdoResultatArtistesSug->fetch(); $intCptArtisteSug++) {
    $arrArtistesSug[$intCptArtisteSug]['id_artiste'] = $ligne['id_artiste'];
    $arrArtistesSug[$intCptArtisteSug]['nom_artiste'] = $ligne['nom_artiste'];
    $arrArtistesSug[$intCptArtisteSug]['provenance'] = $ligne['provenance'];
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
$nbImages = rand(3,5);
var_dump($nbImages);

$classeConteneurMozaique = '';

if($nbImages == 3){
    $classeConteneurMozaique='trois-images';
}else if($nbImages == 4){
    $classeConteneurMozaique='quatre-images';
}else if($nbImages == 5){
    $classeConteneurMozaique='cinq-images';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Fiche de l'artiste</title>
    <link rel="stylesheet" href="../../css/style-elo.css">
    <link rel="stylesheet" href="../../css/menu.css">
</head>
<?php include($niveauFiche . "inc/fragments/header.inc.php") ?>
<body class="body-fiche">

<main id="contenu" class="conteneur">
<div class="fiche">
    <?php $strFilename = $niveauFiche . 'images/photos_artistes/photosFormes/' . $arrArtistes[0]['id_artiste'].'_'. $arrArtistes[0]['nom_artiste']. '_p__w540.jpg';
    if(file_exists($strFilename)){?>
    <picture class="img-principale-artiste">
        <source media="(max-width: 800px)"
               srcset="<?php echo $niveauFiche ?>images/photos_artistes/photosFormes/<?php echo $arrArtistes[0]['id_artiste'].'_'. $arrArtistes[0]['nom_artiste']?>_p__w540.jpg 1x, <?php echo $niveauFiche ?>images/photos_artistes/photosFormes/<?php echo $arrArtistes[0]['id_artiste'].'_'. $arrArtistes[0]['nom_artiste']?>_p__w900.jpg 2x">

        <source media="(min-width: 801px)"
                srcset="<?php echo $niveauFiche ?>images/photos_artistes/photosFormes/<?php echo $arrArtistes[0]['id_artiste'].'_'. $arrArtistes[0]['nom_artiste']?>_p__w600.jpg 1x,<?php echo $niveauFiche ?>images/photos_artistes/photosFormes/<?php echo $arrArtistes[0]['id_artiste'].'_'. $arrArtistes[0]['nom_artiste']?>_p__w1260.jpg 2x">

       <img src="<?php echo $niveauFiche ?>images/photos_artistes/photosFormes/<?php echo $arrArtistes[0]['id_artiste'].'_'. $arrArtistes[0]['nom_artiste'];
        if($arrArtistes[0]['id_artiste'] == 3){
            "_p__w540.webp"; }else{
            "_p__w540.jpg";
       }?>"
        alt="Image de <?php echo $arrArtistes[0]['nom_artiste'] ?>">
    </picture>
    <?php }else{ ?>
    <picture class="img-principale-artiste">
        <source media="(max-width: 800px)"
                srcset="https://via.placeholder.com/540">

        <source media="(min-width: 801px)"
                srcset="https://via.placeholder.com/600">

        <img class="img-principale-artiste"
             src="https://via.placeholder.com/540" alt="Image non-disponible">
    </picture>
    <?php } ?>
<div class="artiste-info">
<ul class="info-sup">
    <li class="nom-artiste">
        <h1 class="h1-fiche"><?php echo $arrArtistes[0]['nom_artiste'];?></h1>
    </li>
    <li class="provenance">
        <p class="h3-fiche"><?php echo $arrArtistes[0]['provenance'];?></p>
    </li>
    <li class="style">
        <h2 class="h3-fiche"><?php echo $strStyles;?></h2>
    </li>
    <li class="site">
        <a class="h3-fiche lien-artiste" href="<?php echo $arrArtistes[0]['site_web_artiste'];?>">Site web de l'artiste</a>
    </li>
</ul>
    <?php if(count($arrLieu)>1){?>
        <ul class="spectacle spectacle1">
            <?php }else{?>
            <ul class="spectacle spectacle0">
            <?php }
            for($cptEnr=0;$cptEnr<count($arrLieu);$cptEnr++){?>
            <li class="date<?php echo $cptEnr?>">
                <p><?php echo $arrLieu[$cptEnr]['date_et_heure'];?></p>
            </li>
            <li class="heure<?php echo $cptEnr?>">
                <p><?php echo $arrLieu[$cptEnr]['heure']?>h<?php echo $arrLieu[$cptEnr]['minute']?></p>
            </li>
            <li class="salle<?php echo $cptEnr?>">
                <p><?php echo $arrLieu[$cptEnr]['nom_lieu'];}?></p>
            </li>
        </ul>
</div>
    <p class="description"><?php echo $arrArtistes[0]['description'];?></p>
</div>
    <div class="mozaique-image <?php echo $classeConteneurMozaique?>">
        <?php for($cpt=0; $cpt<$nbImages;$cpt++){
            $strFilename = $niveauFiche . 'images/photos_artistes/photosSecondaires/'. $arrArtistes[0]['id_artiste'].'_'. $arrArtistes[0]['nom_artiste'].'_'. $cpt . '_' . 'w300.jpg';
            if(file_exists($strFilename)){?>
            <img class="mozaique-image_item" src="<?php echo $niveauFiche ?>images/photos_artistes/photosSecondaires/<?php echo $arrArtistes[0]['id_artiste'].'_'. $arrArtistes[0]['nom_artiste'].'_'. $cpt . '_' . 'w300.jpg'?>" alt="Image<?php echo $cpt . $arrArtistes[0]['nom_artiste'];?>">
        <?php }else{?>
        <img class="mozaique-image_item" src="https://via.placeholder.com/300x240/" alt=" ">
        <?php }
        }?>
    </div>
    <h2 class="artiste-sug-title h2-fiche">Artistes suggérés</h2>
<ul class="suggestion">
    <?php for($cpt=0;$cpt<count($arrArtistesChoisis); $cpt++){?>
            <li class="suggestion_artiste">
                <a class="list-link_artiste" href="<?php echo $niveauFiche ?>artistes/fiche/index.php?id_artiste=<?php echo $arrArtistesChoisis[$cpt]['id_artiste'] ?>">
                    <?php $filename = $niveauFiche . 'images/photos_artistes/photosFormes/'. $arrArtistesChoisis[$cpt]['id_artiste'].'_'. $arrArtistesChoisis[$cpt]['nom_artiste'] . '_p__w360.jpg';
                    if(file_exists($filename)){?>
                    <picture class="img-suggestion-artiste">
                <source media="(max-width: 800px)"
                        srcset="<?php echo $niveauFiche ?>images/photos_artistes/photosFormes/<?php echo $arrArtistesChoisis[$cpt]['id_artiste'].'_'. $arrArtistesChoisis[$cpt]['nom_artiste'] . '_p__w360.jpg';?>">

                <source media="(min-width: 801px)"
                        srcset="<?php echo $niveauFiche ?>images/photos_artistes/photosFormes/<?php echo $arrArtistesChoisis[$cpt]['id_artiste'].'_'. $arrArtistesChoisis[$cpt]['nom_artiste'] . '_p__w540.jpg';?> ">

                <img class="img-suggestion-artiste" src="<?php echo $niveauFiche ?>images/photos_artistes/photosFormes/<?php echo $arrArtistesChoisis[$cpt]['id_artiste'].'_'. $arrArtistesChoisis[$cpt]['nom_artiste'] . '_p__w360.jpg';?> " alt="Image de <?php echo $arrArtistes[0]['nom_artiste'] ?>">
             </picture>
                    <?php }else{?>
                    <img class="img-suggestion-artiste" src="https://via.placeholder.com/360x240/" alt=" ">
                    <?php } ?>
                    <div class="info-artiste-suggere">
                <h3 class="nom-artiste h3-fiche"><?php echo $arrArtistesChoisis[$cpt]['nom_artiste'];?></h3>
                <p class="provenance h4-fiche"><?php echo $arrArtistesChoisis[$cpt]['provenance'];?></p>
            </div>
                </a>
            </li>
    <?php }?>
</ul>
</main>
<footer>
    <?php include($niveauFiche . "inc/fragments/footer.inc.php") ?>
</footer>
<script src="<?php echo $niveauFiche ?>js/menu.js"></script>
</body>
</html>