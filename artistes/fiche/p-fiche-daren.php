<?php
ini_set('display_errors',1);
//Définition de la variable de niveau
$niveau='../../';

//Inclusion du fichier de configuration
include($niveau.'inc/scripts/config.inc.php');


$id_artiste = htmlspecialchars($_GET["id_artiste"]);


//On établi une nouvelle requête pour afficher les enregistrements figurant dans une page donnée
$strRequete =
    'SELECT t_artiste.id_artiste, nom_artiste, provenance, t_artiste.description, site_web_artiste
    FROM t_artiste 
    WHERE t_artiste.id_artiste = '. $id_artiste .'
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
    $arrArtistes[$intCptEnr]['provenance'] = $ligne['provenance'];
    $arrArtistes[$intCptEnr]['description'] = $ligne['description'];
    $arrArtistes[$intCptEnr]['site_web_artiste'] = $ligne['site_web_artiste'];

    //On établi une deuxième requête pour afficher les styles des artistes
    $strRequete =
        'SELECT nom_style, t_style.id_style FROM t_style 
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

    //On établi une troisieme requête pour trouver des artistes du meme style
    $strRequete =
        'SELECT DISTINCT t_artiste.id_artiste, t_artiste.nom_artiste 
            FROM t_artiste 
            INNER JOIN ti_style_artiste 
            ON t_artiste.id_artiste = ti_style_artiste.id_artiste
            WHERE id_style IN(SELECT id_style FROM ti_style_artiste WHERE id_artiste ='. $id_artiste .' AND t_artiste.id_artiste <> '. $id_artiste .')';

    //Initialisation de l'objet PDOStatement et exécution de la requête
    $pdosSous1Resultat = $pdoConnexion->prepare($strRequete);
    $pdosSous1Resultat->execute();

    $ligneStyleAime = $pdosSous1Resultat->fetch();
    //Extraction des noms de Styles de la sous requête

    //On libère la sous requête
    $pdosSous1Resultat->closeCursor();

}
//On libère la requête principale
$pdosResultat->closeCursor();

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des artistes</title>
</head>
<body>
<main>
    <?php
    for($intCpt=0;$intCpt<count($arrArtistes);$intCpt++){?>
        <h1><?php echo $arrArtistes[$intCpt]['nom_artiste'];?></h1>
        <p>Provenance: <?php echo $arrArtistes[$intCpt]['provenance'];?></p>
        <p>Style musicaux: <?php echo $arrArtistes[$intCpt]['nom_style_artiste'];?></p>
        <p>Description: <?php echo $arrArtistes[$intCpt]['description'];?></p>
        <a href="<?php echo $arrArtistes[$intCpt]['site_web_artiste'];?>">Site web de l'artiste</a>
    <?php } ?>
    <div>
        <picture>
            <img src="https://via.placeholder.com/350" alt="image 1">
        </picture>

        <picture>
            <img src="https://via.placeholder.com/250" alt="image 2">
        </picture>

        <picture>
            <img src="https://via.placeholder.com/250" alt="image 3">
        </picture>

        <picture>
            <img src="https://via.placeholder.com/150" alt="image 4">
        </picture>
    </div>
    <p>Date de l'evenement</p>
    <div>
        <h2>Vous aimerez aussi</h2>
        <ul>
            <li>
                <a href="participants/fiches/index.php?id_artiste=">
                    <figure>
                        <img src="https://via.placeholder.com/150" alt="">
                        <figcaption></figcaption>
                    </figure>
                </a>
            </li>
        </ul>
    </div>
</main>
</body>
</html>