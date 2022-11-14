<?php $niveau = '../';

include($niveau . "inc/scripts/config.inc.php");


error_reporting(E_ALL);
ini_set('display_errors', 1);


// NAVIGATION SÉQUENTIELLE
if (isset($_GET['id_page'])) {
    $id_page = $_GET['id_page'];
} else {
    $id_page = 0;
}

if (isset($_GET['idStyle']) == true) {
    $strIdStyle = $_GET['idStyle'];
} else {
    $strIdStyle = 0;
}




$intMaxArtiste = 6;

$enregistrementDepart = $id_page * $intMaxArtiste;


if ($strIdStyle != 0) {
    $strRequetePage = 'SELECT COUNT(*) AS nbEnregistrement FROM t_artiste INNER JOIN ti_style_artiste ON t_artiste.id_artiste=ti_style_artiste.id_artiste WHERE ti_style_artiste.id_style=' . $strIdStyle;
} else {

    $strRequetePage = 'SELECT COUNT(*) AS nbEnregistrement FROM t_artiste';
}
$pdosResultatPage = $pdoConnexion->query($strRequetePage);

$ligne = $pdosResultatPage->fetch();
$intNbArtiste = $ligne['nbEnregistrement'];
$pdosResultatPage->closeCursor();
$nbPages = ceil($intNbArtiste / $intMaxArtiste);
//REQUÊTE LISTE ARTISTES


if ($strIdStyle == 0) {
    $strRequete = 'SELECT nom_artiste, id_artiste FROM t_artiste ORDER BY nom_artiste LIMIT ' . $enregistrementDepart . ',' . $intMaxArtiste;
} else {
    $strRequete = 'SELECT t_artiste.nom_artiste, t_artiste.id_artiste FROM t_artiste INNER JOIN ti_style_artiste ON t_artiste.id_artiste = ti_style_artiste.id_artiste WHERE id_style = ' . $strIdStyle . ' ORDER BY nom_artiste LIMIT ' . $enregistrementDepart . ',' . $intMaxArtiste;
}

$pdosResultat = $pdoConnexion->query($strRequete);


//TABLEAU ARTISTES

$arrTableauxArtiste = array();
$cptArt = 0;
while ($ligne = $pdosResultat->fetch()) {
    $arrTableauxArtiste[$cptArt]['id_artiste'] = $ligne['id_artiste'];
    $arrTableauxArtiste[$cptArt]['nom_artiste'] = $ligne['nom_artiste'];

    $strRequeteStyleArt = 'SELECT nom_style FROM t_style INNER JOIN ti_style_artiste ON t_style.id_style = ti_style_artiste.id_style WHERE id_artiste =' . $ligne['id_artiste'];

    $pdosSousResultat = $pdoConnexion->prepare($strRequeteStyleArt);
    $pdosSousResultat->execute();

    $ligneStyle = $pdosSousResultat->fetch();
    $strStyle = "";

    //BOUCLE STYLES
    for ($intCptStyle = 0; $intCptStyle < $pdosSousResultat->rowCount(); $intCptStyle++) {
        if ($strStyle != "") {
            $strStyle = $strStyle . ", ";    //ajout d'une virgule lorsque nécessaire
        }
        $strStyle = $strStyle . $ligneStyle['nom_style'];
        $ligneStyle = $pdosSousResultat->fetch();
    }
    $pdosSousResultat->closeCursor();

    $arrTableauxArtiste[$cptArt]['style_artiste'] = $strStyle;

    $cptArt++;
}
$pdosResultat->closeCursor();


// REQUÊTE STYLES
$strRequeteStyle = 'SELECT id_style, nom_style FROM t_style';

$pdosResultatStyle = $pdoConnexion->query($strRequeteStyle);


//TABLEAU STYLES

$arrStyle = array();
$cptEnrStyle = 0;
while ($ligne = $pdosResultatStyle->fetch()) {
    $arrStyle[$cptEnrStyle]['id_style'] = $ligne['id_style'];
    $arrStyle[$cptEnrStyle]['nom_style'] = $ligne['nom_style'];
    $cptEnrStyle++;
}
$pdosResultat->closeCursor();


//REQUÊTE SUGGESTIONS

$strRequeteSug = 'SELECT nom_artiste, id_artiste FROM t_artiste ORDER BY nom_artiste';
$pdosResultat = $pdoConnexion->prepare($strRequeteSug);
$pdosResultat->execute();


$arrTableauxSuggestion = array();
$cptSug = 0;
while ($ligne = $pdosResultat->fetch()) {
    $arrTableauxSuggestion[$cptSug]['id_artiste'] = $ligne['id_artiste'];
    $arrTableauxSuggestion[$cptSug]['nom_artiste'] = $ligne['nom_artiste'];

    $strRequeteStyleArtSug = 'SELECT nom_style FROM t_style INNER JOIN ti_style_artiste ON t_style.id_style = ti_style_artiste.id_style WHERE id_artiste =' . $ligne['id_artiste'];

    $pdosSousResultat = $pdoConnexion->prepare($strRequeteStyleArtSug);
    $pdosSousResultat->execute();

    $ligneStyle = $pdosSousResultat->fetch();
    $strStyleSug = "";

    //BOUCLE STYLES
    for ($intCptStyleSug = 0; $intCptStyleSug < $pdosSousResultat->rowCount(); $intCptStyleSug++) {
        if ($strStyleSug != "") {
            $strStyleSug = $strStyleSug . ", ";    //ajout d'une virgule lorsque nécessaire
        }
        $strStyleSug = $strStyleSug . $ligneStyle['nom_style'];
        $ligneStyle = $pdosSousResultat->fetch();
    }
    $pdosSousResultat->closeCursor();

    $arrTableauxSuggestion[$cptSug]['style_artiste'] = $strStyleSug;


    $cptSug++;
}

$nbSuggestion = 2;
$arrArtisteChoisi = array();
for ($intCptArtChoisi = 0; $intCptArtChoisi < $nbSuggestion; $intCptArtChoisi++) {
    $intIndexHasard = rand(0, count($arrTableauxSuggestion) - 1);
    array_push($arrArtisteChoisi, $arrTableauxSuggestion[$intIndexHasard]);
    array_splice($arrTableauxSuggestion, $intIndexHasard, 1);
}

$pdosResultat->closeCursor();

?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Liste_Artistes</title>
    <link rel="stylesheet" href="<?php echo $niveau ?>css/style-marianne.css">
<!--    <link rel="stylesheet" href="--><?php //echo $niveau ?><!--css/menu.css">-->
    <?php include($niveau . "inc/fragments/header.inc.php") ?>
</head>
<body >



<main class="main conteneur">
<div class="entete">
    <?php include($niveau . "inc/fragments/svg.inc.php") ?>
    <h1 class="h1-artistes ">ARTISTES</h1>
    <div class="select_style">
        <div class="select_sc">
        <select class="list_style" id="list_style">
            <option value="style_label">STYLES</option>
            <option value="0">Tous les styles</option>
            <?php
            //AFFICHER STYLES
            for ($intCpt = 0;
                 $intCpt < count($arrStyle);
                 $intCpt++) {
                ?>

                <option value="<?php echo $arrStyle[$intCpt]['id_style'] ?>"> <?php echo $arrStyle[$intCpt]['nom_style'] ?> </option>


                <?php
            }
            ?>
        </select>

        <script type="text/javascript">

            var urlmenu = document.getElementById('list_style');
            window.name = Math.random().toString(36);
            urlmenu.onchange = function () {
                window.open("index.php?idStyle=" + this.options[this.selectedIndex].value, window.name);
            };
        </script>
        </div>
    </div>


    <?php
    if ($strIdStyle != 0) {
        ?>
        <p class="typeStyle"> Artiste avec le style <?php echo $arrStyle[($strIdStyle - 1)]['nom_style'] ?></p>

        <?php
    }
    ?>
</div>


<ul class="liste_artistes">
            <?php
            //AFFICHER ARTISTES
            for ($intCpt = 0;
            $intCpt < count($arrTableauxArtiste);
            $intCpt++){
                if($arrTableauxArtiste[$intCpt]['id_artiste'] == 3) {
                    $strNomFichier = $niveau . 'images/photos_artistes/photosFormes/' . $arrTableauxArtiste[$intCpt]['id_artiste'] . '_' . $arrTableauxArtiste[$intCpt]['nom_artiste'] . '_p__w540.webp';
                }else{
                    $strNomFichier = $niveau . 'images/photos_artistes/photosFormes/' . $arrTableauxArtiste[$intCpt]['id_artiste'] . '_' . $arrTableauxArtiste[$intCpt]['nom_artiste'] . '_p__w540.jpg';
                }
            ?>
                <li class="liste-item_artistes">
                    <?php  if(file_exists($strNomFichier)){ ?>
                   <a href="fiche/index.php?id_artiste=<?php echo $arrTableauxArtiste[$intCpt]['id_artiste'] ?>"> <picture class="picture_art">


                        <source media="(min-width:800px)" srcset="<?php echo $niveau ?>images/photos_artistes/photosFormes/<?php echo $arrTableauxArtiste[$intCpt]['id_artiste'] ?>_<?php echo $arrTableauxArtiste[$intCpt]['nom_artiste'] ?>_p__w900.<?php if ($arrTableauxArtiste[$intCpt]['id_artiste'] == 3){  ?>webp<?php }else{ ?>jpg<?php } ?>">
                        <source media="(max-width:800px)" srcset="<?php echo $niveau ?>images/photos_artistes/photosFormes/<?php echo $arrTableauxArtiste[$intCpt]['id_artiste'] ?>_<?php echo $arrTableauxArtiste[$intCpt]['nom_artiste'] ?>_p__w540.<?php if ($arrTableauxArtiste[$intCpt]['id_artiste'] == 3){  ?>webp<?php }else{ ?>jpg<?php } ?>">
                        <img src="<?php echo $niveau ?>images/photos_artistes/photosFormes/<?php echo $arrTableauxArtiste[$intCpt]['id_artiste'] ?>_<?php echo $arrTableauxArtiste[$intCpt]['nom_artiste'] ?>_p__w900.<?php if ($arrTableauxArtiste[$intCpt]['id_artiste'] == 3){  ?>webp<?php }else{ ?>jpg<?php } ?>" alt="Photo de <?php echo $arrTableauxArtiste[$intCpt]['nom_artiste'] ?>" style="width:auto;">
                    </picture>
                   </a>
                    <?php }else{ ?>
                    <a href="fiche/index.php?id_artiste=<?php echo $arrTableauxArtiste[$intCpt]['id_artiste'] ?>"> <picture class="picture_art">


                            <source media="(min-width:800px)" src="https://via.placeholder.com/900x900/ff0000">
                            <source media="(max-width:800px)" src="https://via.placeholder.com/540x540/ff0000">
                            <img src="https://via.placeholder.com/900x900/ff0000" alt="Photo de <?php echo $arrTableauxArtiste[$intCpt]['nom_artiste'] ?>" style="width:auto;">
                        </picture>
                    </a>
                    <?php  } ?>
                    <div class="liste-info_artistes">
                        <h2 class="h2-artistes">
                            <a class="list-link_artiste"
                               href="fiche/index.php?id_artiste=<?php echo $arrTableauxArtiste[$intCpt]['id_artiste'] ?>">
                                <b><?php
                                    echo $arrTableauxArtiste[$intCpt]['nom_artiste'] ?></b>
                            </a>
                        </h2>
                        <p class="styleArt"><b><?php echo $arrTableauxArtiste[$intCpt]['style_artiste']; ?></b></p>
                    </div>
                </li>

                <?php
                }
                ?>
</ul>


    <div class="nav-seq">
    <?php if ($id_page > 0) {
        //Si la page courante n'est pas la première, afficher bouton précédent?>
        <a class="link_precedent"
           href='index.php?id_page=<?php echo($id_page - 1); ?>&idStyle=<?php echo $strIdStyle; ?>'>Précédent</a>
    <?php } ?>


        <?php

        if ($nbPages > 1) {
            for ($cpt = 0; $cpt < $nbPages; $cpt++) {
                //Si la page courante n'est pas la dernière, afficher bouton suivant
                ?>
                <a class="link_number"
                   href='index.php?id_page=<?php echo $cpt; ?>&idStyle=<?php echo $strIdStyle; ?>'><?php echo($cpt + 1); ?></a>
            <?php }
        }

        if ($id_page < $nbPages - 1) { ?>
            <!--Si la page courante n'est pas la première, afficher bouton précédent -->
            <a class="link_suivant"
               href='index.php?id_page=<?php echo($id_page + 1); ?>&idStyle=<?php echo $strIdStyle; ?>'>Suivant</a>
        <?php } ?>
    </div>




    <h2 class="h2-artistes h2_sug"> SUGGESTIONS </h2>
    <div class="suggestions">

            <?php
            //AFFICHER SUGGESTIONS
            for ($intCptSug = 0;
            $intCptSug < count($arrArtisteChoisi);
            $intCptSug++){
                if($arrArtisteChoisi[$intCptSug]['id_artiste'] == 3) {
                    $strNomFichier = $niveau . 'images/photos_artistes/photosFormes/' . $arrArtisteChoisi[$intCptSug]['id_artiste'] . '_' . $arrArtisteChoisi[$intCptSug]['nom_artiste'] . '_p__w540.webp';
                }else{
                    $strNomFichier = $niveau . 'images/photos_artistes/photosFormes/' . $arrArtisteChoisi[$intCptSug]['id_artiste'] . '_' . $arrArtisteChoisi[$intCptSug]['nom_artiste'] . '_p__w540.jpg';
                }

            ?>
            <div class="box_sug">
                <?php  if(file_exists($strNomFichier)){ ?>
                    <a href="fiche/index.php?id_artiste=<?php echo $arrArtisteChoisi[$intCptSug]['id_artiste'] ?>"> <picture class="picture_art">


                            <source media="(min-width:800px)" srcset="<?php echo $niveau ?>images/photos_artistes/photosFormes/<?php echo $arrArtisteChoisi[$intCptSug]['id_artiste'] ?>_<?php echo $arrArtisteChoisi[$intCptSug]['nom_artiste'] ?>_p__w360.<?php if ($arrArtisteChoisi[$intCptSug]['id_artiste'] == 3){  ?>webp<?php }else{ ?>jpg<?php } ?>">
                            <source media="(max-width:800px)" srcset="<?php echo $niveau ?>images/photos_artistes/photosFormes/<?php echo $arrArtisteChoisi[$intCptSug]['id_artiste'] ?>_<?php echo $arrArtisteChoisi[$intCptSug]['nom_artiste'] ?>_p__w360.<?php if ($arrArtisteChoisi[$intCptSug]['id_artiste'] == 3){  ?>webp<?php }else{ ?>jpg<?php } ?>">
                            <img src="<?php echo $niveau ?>images/photos_artistes/photosFormes/<?php echo $arrArtisteChoisi[$intCptSug]['id_artiste'] ?>_<?php echo $arrArtisteChoisi[$intCptSug]['nom_artiste'] ?>_p__w360.<?php if ($arrArtisteChoisi[$intCptSug]['id_artiste'] == 3){  ?>webp<?php }else{ ?>jpg<?php } ?>" alt="Photo de <?php echo $arrArtisteChoisi[$intCptSug]['nom_artiste'] ?>" style="width:auto;">
                        </picture>
                    </a>
                <?php }else{ ?>
                <a href="fiche/index.php?id_artiste=<?php echo $arrArtisteChoisi[$intCptSug]['id_artiste'] ?>"> <picture class="picture_art">


                        <source media="(min-width:800px)" src="https://via.placeholder.com/360x360/ff0000">
                        <source media="(max-width:800px)" src="https://via.placeholder.com/360x360/ff0000">
                        <img src="https://via.placeholder.com/360x360/ff0000" alt="Photo de <?php echo $arrArtisteChoisi[$intCptSug]['nom_artiste'] ?>" style="width:auto;">
                    </picture>
                </a>
                <?php  }  ?>
                <h3 class="h3-artistes">
                    <a class="list-link_sug"
                       href="fiche/index.php?id_artiste=<?php echo $arrArtisteChoisi[$intCptSug]['id_artiste'] ?>">
                        <?php
                        echo $arrArtisteChoisi[$intCptSug]['nom_artiste'];
                        ?>

                    </a>
                </h3>
                <p class="styleArt_sug"> <?php echo $arrArtisteChoisi[$intCptSug]['style_artiste']; ?></p>

            </div>
                <?php
                } ?>
    </div>


</main>


<footer>
    <?php include($niveau . "inc/fragments/footer.inc.php") ?>
</footer>
<script src="<?php echo $niveau ?>js/menu.js"></script>
</body>
</html>
