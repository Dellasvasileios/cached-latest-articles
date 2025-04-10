<?php

require_once plugin_dir_path(__FILE__) . '../inc/CLA_Cache_Garbage_Collector.php';

function CLA_options_page() {
    add_menu_page(
        'Cached Latest Articles',
        'Cached Latest Articles',
        'manage_options',
        'CLA-options',
        'CLA_render_options_page',
        'dashicons-admin-generic',
        10
    );
}
add_action('admin_menu', 'CLA_options_page');

function CLA_render_options_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['submit']) && isset($_POST['cla_options_is_active'])) {
        foreach($_POST['cla_options_is_active'] as $index=>$cla_option_is_active){
            $cla_options_id = $_POST['cla_options_id'][$index];
            $cla_options_refresh_on_publish = $_POST['cla_options_refresh_on_publish'][$index];
            $cla_options_refresh_on_update = $_POST['cla_options_refresh_on_update'][$index];
            $cla_options_refresh_on_delete = $_POST['cla_options_refresh_on_delete'][$index];
            $cla_options_number_of_posts = $_POST['cla_options_number_of_posts'][$index];
            $cla_options_ajax = $_POST['cla_options_ajax'][$index];
            $cla_options_card = $_POST['cla_options_card'][$index];

            $options[] = [
                'is_active' => $cla_option_is_active,
                'id' => $cla_options_id,
                'refresh_on_publish' => $cla_options_refresh_on_publish,
                'refresh_on_update' => $cla_options_refresh_on_update,
                'refresh_on_delete' => $cla_options_refresh_on_delete,
                'number_of_posts' => $cla_options_number_of_posts,
                'ajax' => $cla_options_ajax,
                'view_type' => $cla_options_card
            ];

            
        }

        update_option('cla_options', $options);
        $options = !empty(get_option('cla_options')) ? get_option('cla_options') : [];
        
        $garbage_collector = new CLA_Cache_Garbage_Collector(CLA_PLUGIN_DIR . 'cache/', new CLA_File_Handler());
        $garbage_collector->clear_unused_cache_files($options);

        foreach($options as $option){

            $CLA_Posts = new CLA_Posts((int)$option['number_of_posts']);

            $CLA_Cache_Handler = new CLA_Cache_Handler(
                $option['id'],
                $CLA_Posts,
                new CLA_File_Handler(),
                CLA_PLUGIN_DIR . 'cache/'
            );

            $CLA_Cache_Handler->refresh_cache();
        }

        echo '<p class="save_changes_nodice">Changes Saved, cache renewed</p>';
    }

    $options = get_option('cla_options');
    $options = !empty($options) ? $options : [];
    ?>
    <div class="wrap">
        <h1>Cached Latest Articles Settings</h1>
        <form method="post" action="">

            <div class="settings">  
                <div class="settings__inner">
                    <?php
                        if(!empty($options)) :
                            foreach ($options as $index => $option) : 

                                echo settingsGroupItem($option,$index);

                            endforeach;
                        else:  
                        echo 'else';
                        echo settingsGroupItem([],0);

                        endif; 
                    ?>
                </div>
                <div class="settings__footer">
                    <span class=" settings_newShortcode button button-primary">Add shortcode</span>
                </div>
            </div>
            <?php submit_button(); ?>
           
        </form>
    </div>
    <?php
}


function settingsGroupItem($options, $index = 0) {

    $output = "";
    ob_start();
    ?>
        <div class="settingsGroup" >
            <div class="settingsGroup__control">
                <span class="settingsGroup__move">
                    <span class="settingsGroup__move-left">⬅️</span>
                    <span class="settingGroup__move-right">➡️</span>
                </span>
                <span class="settingsGroup__delete">❌</span>
            </div>
            <div class="settingsGroup__item">
                <label class="settingsGroup__checkbox">
                    <span>Is active</span> 
                    <input type="hidden" name="cla_options_is_active[]" value="<?=!empty($options["is_active"]) ? $options['is_active'] : '0'?>" />
                    <input type="checkbox" name="" <?= !empty($options["is_active"]) && $options["is_active"] == '1' ? 'checked="checked"': ''  ?> />
                </label>
            </div>
            
            <div  class="settingsGroup__item">
                <label class="settingsGroup__input-regular">
                    <span>ID</span>
                    <input readonly   class="indexinput" type="number" name="cla_options_id[]" value="<?=!empty($options["id"]) ? $options['id'] : '1'?>" />
                </label>
            </div>
            <div class="settingsGroup__item">
                <label class="settingsGroup__checkbox">
                    <span>Refresh on publish(and scheduled)</span> 
                    <input type="hidden" name="cla_options_refresh_on_publish[]" value="<?=!empty($options["refresh_on_publish"]) ? $options['refresh_on_publish'] : '0'?>" />
                    <input type="checkbox" name="" <?= !empty($options["is_active"]) && $options["refresh_on_publish"] == '1' ? 'checked="checked"': ''  ?> />
                </label>
            </div>
            <div class="settingsGroup__item">
                <label class="settingsGroup__checkbox">
                    <span> Refresh on update</span> 
                    <input type="hidden" name="cla_options_refresh_on_update[]" value="<?=!empty($options["refresh_on_update"]) ? $options['refresh_on_update'] : '0'?>" />
                    <input type="checkbox" name="" <?= !empty($options["refresh_on_update"]) && $options["refresh_on_update"] == '1' ? 'checked="checked"': ''  ?> />
                </label>
            </div> 
            <div class="settingsGroup__item">
                <label class="settingsGroup__checkbox">
                    <span> Refresh on delete</span>
                    <input type="hidden" name="cla_options_refresh_on_delete[]" value="<?=!empty($options["refresh_on_delete"]) ? $options['refresh_on_delete'] : '0'?>" />
                    <input type="checkbox" name="" <?= !empty($options["refresh_on_delete"]) && $options["refresh_on_delete"] == '1' ? 'checked="checked"': ''  ?> />
                </label>
            </div>
            <div class="settingsGroup__item">
                <label class="settingsGroup__input-regular">
                    <span>Numper of posts (max 100)*</span>
                    <input type="number" name="cla_options_number_of_posts[]" value="<?=!empty($options["number_of_posts"]) ? $options['number_of_posts'] : '0'?>" />
                </label> 
            </div>
            <div class="settingsGroup__item">
                <label class="settingsGroup__checkbox">
                    <span>Show latest articles with ajax</span>
                    <input type="hidden" name="cla_options_ajax[]" value="<?=!empty($options["ajax"]) ? $options['ajax'] : '0'?>"  />
                    <input type="checkbox" name="" <?= !empty($options["ajax"]) && $options["ajax"] == '1' ? 'checked="checked"': '' ?> />
                </label>
            </div>

            <div class="settingsGroup__item">
                
                <?php
                    if(empty($options["view_type"])){
                        $options["view_type"] = 'card';
                    }
                ?>

                <input type="hidden" name="cla_options_card[]" value="<?=$options["view_type"]?>" />

                <label class="settingsGroup__radio ">
                    <span>View as card</span>
                    
                    <input class="settingsGroup__radio-view" name='view_type[<?=$index?>]' type="radio"  <?= !empty($options["view_type"]) && $options["view_type"] == 'card' ? 'checked="checked"': ''  ?> value="card" />
                </label>
                <label class="settingsGroup__radio">
                    <span>View as List</span>
                    <input class="settingsGroup__radio-view" name='view_type[<?=$index?>]' type="radio" <?= !empty($options["view_type"]) && $options["view_type"] == 'list' ? 'checked="checked"': ''  ?>  value="list" />
                </label>
            </div>


            
            <code>
                [cached_latest_articles  id="<?=$options['id']?>" view="<?=$options['view_type']?>" number_of_posts="<?=$options['number_of_posts']?>" ajax="<?=$options['ajax']?>"]
            </code>

        </div>
    <?php

    return  $output = ob_get_clean();

}