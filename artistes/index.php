<?php if ($strIdPage > 0) { ?>
            <a href="artistes/p-artiste-daren.php?id_page=<?php echo ($strIdPage-1);?>&id_style= <?php echo $strIdStyle;?>">Précédent</a>
        <?php } ?>

        <?php if ($strIdPage > 1) { ?>
            <a href="artistes/p-artiste-daren.php?id_page=<?php echo $cpt;?>&id_style= <?php echo $strIdStyle;?>" <?php echo ($cpt+1);?>></a>
        <?php } ?>

        <?php if ($strIdPage < $nbPages-1) { ?>
            <a href="artistes/p-artiste-daren.php?id_page=<?php echo ($strIdPage+1);?>&id_style= <?php echo $strIdStyle;?>">Suivant</a>
        <?php } ?>