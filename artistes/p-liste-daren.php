<?php
//Définition de la variable de niveau
$niveau='../';

//Inclusion du fichier de configuration
include($niveau.'inc/scripts/config.inc.php');

//Récupérer le numéro de la page dans la querystring
if (isset($_GET['id_page'])==true) {
    $strIdPage = $_GET['id_page'];
} else {
    $strIdPage = 0;
}

//nombre de participant par page
$nbArtisteParPage =3;
//Calculer l'index du premier enregistrement à afficher
$enregistrementDepart = $strIdPage * $nbArtisteParPage;

if ($strIdStyle !=0) {
    $strRequete = 'SELECT COUNT(*) AS nbEnregistrement
    FROM t_artiste
    INNER JOIN ti_style_artiste ON t_artiste.id_artiste = ti_style_artiste.id_artiste
    WHERE ti_style_artiste.id_style = '. $strIdStyle'';
} else {
    $strRequete = 'SELECT COUNT(*) AS nbEnregistrement FROM t_artiste';
}

if($strIdStyle !=0) {
    $strRequete = 'SELECT t_artiste.id_artiste, nom_artiste
    FROM t_artiste
    INNER JOIN ti_style_artiste ON t_artiste.id_artiste = ti_style_artiste.id_artiste
    WHERE ti_style_artiste.id_style = '. $strIdStyle .'
    ORDER BY nom_artiste
    LIMIT '. $enregistrementDepart .', '. $nbArtisteParPage'';
} else {
    $strRequete =  'SELECT id_artiste, nom_artiste
					FROM t_artiste 
					ORDER BY nom_artiste
                    LIMIT '. $enregistrementDepart .', '. $nbArtisteParPage'';
}

$pdosResultatCpt = $pdoConnexion->query($strRequete);
$totalArtistes = $pdosResultatCpt-> fetch();
$nbArtistes = $totalArtistes['nbEnregistrement'];
$pdosResultatCpt->closeCursor();

$nbPages = ceil($nbArtistes / $nbArtisteParPage);

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
    <header>
        <nav class="nav">
            <ul class="nav__list">
                <li class="list__item"><a class="list__lien" href="../p-accueil-daren.php">Accueil</a></li>
                <li class="list__item"><a class="list__lien" href="../programmation/p-programmation-daren.php">Programmation</a></li>
                <li class="list__item"><a class="list__lien" href="#">Artistes</a></li>
                <li class="list__item"><a class="list__lien" href="#">Partenaires</a></li>
            </ul>
            <a class="lien-passeport" href="#">Acheter mon passeport</a>
        </nav>
    </header>
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
                        <a href='fiche/p-fiche-daren.php?id_artiste=<?php echo $arrArtistes[$intCpt]['id_artiste']?>'>
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
