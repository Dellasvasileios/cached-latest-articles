<?php
    $cardClass = 'CLACardList'. $atts['id'];
    $cardLink = $post['permalink'];
    $date = date('d/m/Y - G:i', strtotime($post['post_date']));
    $image_url = $post['image_url'];
    $excerpt = $post['post_excerpt'];
    $title = $post['post_title'];
?>
<li class="<?=$cardClass?>__item">
    <a href="<?=$cardLink?>">
        <p><?=$title?></p>
        <time class="<?=$cardClass?>__time">
            <?=$date?>             
        </time>
    </a>
</li>