<?php
ini_set('display_errors',1);
//Définition de la variable de niveau
$niveau='../';

//Inclusion du fichier de configuration
include($niveau.'inc/scripts/config.inc.php');

//On établi une nouvelle requête pour afficher les enregistrements figurant dans une page donnée
$strRequete =  'SELECT id_artiste, nom_artiste
					FROM t_artiste 
					ORDER BY nom_artiste';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosResultat = $pdoConnexion->prepare($strRequete);
$pdosResultat->execute();

$arrArtistes=array();
$ligne=$pdosResultat->fetch();

//Extraction des enregistrements à afficher de la BD
for($intCptEnr=0;$intCptEnr<$pdosResultat->rowCount();$intCptEnr++){
    $arrArtistes[$intCptEnr]['id_artiste'] = $ligne['id_artiste'];
    $arrArtistes[$intCptEnr]['nom_artiste'] = $ligne['nom_artiste'];

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
    $arrArtistes[$intCptEnr]['nom_style_artiste'] = $strStylesArtiste;

    //On passe à l'autre style
    $ligne=$pdosResultat->fetch();

}
//On libère la requête principale
$pdosResultat->closeCursor();


$strRequete =  'SELECT id_style, nom_style
	FROM t_style';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosStyleResultat = $pdoConnexion->prepare($strRequete);
$pdosStyleResultat->execute();

$arrStyles=array();
$ligne=$pdosStyleResultat->fetch();

for($intCptEnr2=0;$intCptEnr2<$pdosStyleResultat->rowCount();$intCptEnr2++){
    $arrStyles[$intCptEnr2]['nom_style'] = $ligne['nom_style'];
    $ligne=$pdosStyleResultat->fetch();
}

//On libère la requête des styles
$pdosStyleResultat->closeCursor();


?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des artistes</title>
</head>
<body>
<h1>Page artiste</h1>
<main>
    <h2>Liste des styles</h2>
    <ul>
        <?php
        for($intCptStyle=0;$intCptStyle<count($arrStyles);$intCptStyle++){?>
            <li>
                <a href=""><?php echo $arrStyles[$intCptStyle]['nom_style'];?></a>
            </li>
        <?php } ?>
    </ul>
    <h2>Liste des artistes</h2>
    <ul>
        <?php
        for($intCpt=0;$intCpt<count($arrArtistes);$intCpt++){?>
            <li>
                <figure>
                    <a href='/Proto_OFF/artistes/fiche/p-fiche-daren.php?id_artiste=<?php echo $arrArtistes[$intCpt]['id_artiste']?>'>
                        <img src="https://via.placeholder.com/150" alt="">
                        <figcaption><?php echo $arrArtistes[$intCpt]['nom_artiste'];?></figcaption>
                    </a>
                    <p> Style: <?php echo $arrArtistes[$intCpt]['nom_style_artiste'];?></p>
                </figure>
            </li>
        <?php } ?>
    </ul>

</main>
</body>
</html>
