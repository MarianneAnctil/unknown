<?php
//Définition de la variable de niveau
$niveau='../';

//Inclusion du fichier de configuration
include($niveau.'inc/scripts/config.inc.php');

//On établi une nouvelle requête pour afficher les enregistrements figurant dans une page donnée
$strRequete =  'SELECT ti_evenement.id_lieu, ti_evenement.id_artiste, t_lieu.nom_lieu, t_artiste.nom_artiste, date_et_heure,
    DAYOFMONTH(date_et_heure) AS Jour,
    HOUR(date_et_heure) AS Heure,
    MINUTE(date_et_heure) AS Minute
    FROM ti_evenement
    INNER JOIN t_lieu 
    ON t_lieu.id_lieu = ti_evenement.id_lieu
    INNER JOIN t_artiste 
    ON t_artiste.id_artiste = ti_evenement.id_artiste
';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosResultat = $pdoConnexion->prepare($strRequete);
$pdosResultat->execute();

$arrEvenement=array();
$ligne=$pdosResultat->fetch();

//Extraction des enregistrements à afficher de la BD
for($intCptEnr=0;$intCptEnr<$pdosResultat->rowCount();$intCptEnr++){
    $arrEvenement[$intCptEnr]['id_lieu'] = $ligne['id_lieu'];
    $arrEvenement[$intCptEnr]['nom_lieu'] = $ligne['nom_lieu'];
    $arrEvenement[$intCptEnr]['id_artiste'] = $ligne['id_artiste'];
    $arrEvenement[$intCptEnr]['nom_artiste'] = $ligne['nom_artiste'];
    $arrEvenement[$intCptEnr]['date_et_heure'] = $ligne['date_et_heure'];
    $arrEvenement[$intCptEnr]['Jour'] = $ligne['Jour'];
    $arrEvenement[$intCptEnr]['Heure'] = $ligne['Heure'];
    $arrEvenement[$intCptEnr]['Minute'] = $ligne['Minute'];

    //On établi une deuxième requête pour afficher les styles des artistes
    $strRequete =
        'SELECT nom_style FROM t_style 
			INNER JOIN ti_style_artiste ON ti_style_artiste.id_style = t_style.id_style 
			WHERE id_artiste='. $ligne['id_artiste'];

    //Initialisation de l'objet PDOStatement et exécution de la requête
    $pdosSousResultat = $pdoConnexion->prepare($strRequete);
    $pdosSousResultat->execute();

    $ligneStyleArtiste = $pdosSousResultat->fetch();
    $strStylesArtiste="";
    //Extraction des noms de Styles de la sous requête
    for($intCptStyleArtiste=0;$intCptStyleArtiste<$pdosSousResultat->rowCount();$intCptStyleArtiste++){
        if($strStylesArtiste != ""){
            $strStylesArtiste = $strStylesArtiste . ", ";    //ajout d'une virgule lorsque nécessaire
        }
        $strStylesArtiste = $strStylesArtiste . $ligneStyleArtiste['nom_style'];
        $ligneStyleArtiste = $pdosSousResultat->fetch();
    }
    //On libère la sous requête
    $pdosSousResultat->closeCursor();

    //ajout d'une propriété pour afficher le style des artistes
    $arrEvenement[$intCptEnr]['nom_style_artiste'] = $strStylesArtiste;

    //On passe à l'autre style
    $ligne=$pdosResultat->fetch();
    
}
//On libère la requête principale
$pdosResultat->closeCursor();

//Extraction des lieux
$strRequete =
'SELECT DISTINCT id_lieu, nom_lieu
 FROM t_lieu';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosLieuResultat = $pdoConnexion->prepare($strRequete);
$pdosLieuResultat->execute();

$arrLieux=array();
$ligne=$pdosLieuResultat->fetch();

for($intCptEnr2=0;$intCptEnr2<$pdosLieuResultat->rowCount();$intCptEnr2++){
    $arrLieux[$intCptEnr2]['id_lieu'] = $ligne['id_lieu'];
    $arrLieux[$intCptEnr2]['nom_lieu'] = $ligne['nom_lieu'];
    $ligne=$pdosLieuResultat->fetch();
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des artistes</title>
</head>
<body>
    <header>
        <nav class="nav">
            <ul class="nav__list">
                <li class="list__item"><a class="list__lien" href="../p-accueil-daren.php">Accueil</a></li>
                <li class="list__item"><a class="list__lien" href="#">Programmation</a></li>
                <li class="list__item"><a class="list__lien" href="../artistes/p-liste-daren.php">Artistes</a></li>
                <li class="list__item"><a class="list__lien" href="#">Partenaires</a></li>
            </ul>
            <a class="lien-passeport" href="#">Acheter mon passeport</a>
        </nav>
    </header>
    <main>
        <h1>Programmation</h1>
        <h2>Date</h2>
        <p>8 9 10 11</p>
        <ul>
            <?php
            for($intCpt=0; $intCpt < count($arrLieux);$intCpt++){
            ?>
            <li><?php echo $arrLieux[$intCpt]['nom_lieu'];?>
                <ul>
                    <?php
                    for($intCpt2=0; $intCpt2 < count($arrEvenement);$intCpt2++){
                        //Ajoute un 0 lorsque les minutes sont a 0 pour afficher 00 apres l'heure
                        if ($arrEvenement[$intCpt2]['Minute'] == '0') {
                            $arrEvenement[$intCpt2]['Minute'] = '00';
                        }
                    ?>
                    <li><?php echo $arrEvenement[$intCpt2]['Heure'];?> h <?php echo $arrEvenement[$intCpt2]['Minute'];?>, <a href="../artistes/fiche/p-fiche-daren.php?id_artiste=<?php echo $arrEvenement[$intCpt2]['id_artiste'];?>"><?php echo $arrEvenement[$intCpt2]['nom_artiste'];?></a>, <?php echo $arrEvenement[$intCpt]['nom_style_artiste'];?></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </main>
</body>

