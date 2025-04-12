<?php

function shortcode_cached_latest_articles($atts){
    $atts = shortcode_atts(array(
        'id' => 1,
        'view' => 'card',
        'number_of_posts' => 5,
        'ajax' => '0',
        'is_active' => '1',
    ), $atts, 'cached_latest_articles');

    if( $atts['is_active'] == '0'){
        return;
    }

    $cache = false;

    $cache_file = CLA_PLUGIN_DIR . 'cache/' . 'articles-cache' . $atts['id'] . '.json';

    if(file_exists($cache_file)){
        $cache = file_get_contents(CLA_PLUGIN_DIR . 'cache/' . 'articles-cache' . $atts['id'] . '.json', true);
    }
    
    if($cache === false){
        return ;
    }

    $posts = json_decode($cache, true);

    $output = '';
    
    if($atts['ajax'] == '0'){
        foreach($posts as $index => $post){
            
            ob_start();
            if($atts['view'] == 'card'){
                    require CLA_PLUGIN_DIR . 'templates/content-card.php';
            }else{
                require CLA_PLUGIN_DIR . 'templates/content-list.php';  
            }   
            
            $output =  $output . ob_get_clean();
        }

        if($atts['view'] == 'list'){
            $output = '<ul class="CLACard'.$atts['id'].'">' . $output . '</ul>';
        }
    }
    else{
        
        $output = '<div data-cla-view-type='.$atts['view'].' data-ajax="true" data-CLA-id="'.$atts['id'].'" class="CLACardAjaxWrapper'.$atts['id'].'"></div>';
        
    }

    return $output;
}

add_shortcode('cached_latest_articles', 'shortcode_cached_latest_articles');