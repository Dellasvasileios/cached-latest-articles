<?php
    $cardClass = 'CLACard'. $atts['id'];
    $cardLink = $post['permalink'];
    $date = date('d/m/Y - G:i', strtotime($post['post_date']));
    $image_url = $post['image_url'];
    $excerpt = $post['post_excerpt'];
    $title = $post['post_title'];
?>
<article class="<?=$cardClass?>">
    <div class="<?=$cardClass?>__image ">
        <?php 
            if(!empty($image_url)):
        ?>
            <a href="<?=$cardLink?>">
            <figure class="<?=$cardClass?>__figure">
                <img src="<?= $image_url?>" alt="Image" />">
            </figure>
        </a>
        <?php
            endif;
        ?>
    </div>

    <div class="<?=$cardClass?>__content ">
        <div class="<?=$cardClass?>__details ">   
            <time class="<?=$cardClass?>__time">
                <?=$date?>             
            </time>
        </div>
        <h3 class="<?=$cardClass?>__title ">
            <a href="<?=$cardLink?>" class="<?=$cardClass?>__link">
                <?=$title?>
            </a>
        </h3>
        <div class="<?=$cardClass?>__excerpt ">
            <?= $excerpt ?>
        </div>
    </div>
</article>