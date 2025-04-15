<?php
class CLA_Event_Handler{

    private $refresh_on_publish;
    private $refresh_on_update;
    private $refresh_on_delete;
    private $is_active;

    private $CLA_Cache_Handler;
    private $CLA_Posts;

    function __construct($CLA_Cache_Handler, $CLA_Posts, $refresh_on_publish, $refresh_on_update, $refresh_on_delete, $is_active){

        $this->CLA_Cache_Handler = $CLA_Cache_Handler;
        $this->CLA_Posts = $CLA_Posts;
        
        $this->refresh_on_publish = $refresh_on_publish;
        $this->refresh_on_update = $refresh_on_update;
        $this->refresh_on_delete = $refresh_on_delete;
        $this->is_active = $is_active;


        if($this->is_active == '0'){
            return;
        }

        if($this->refresh_on_publish == '1' || $this->refresh_on_update == '1'){
            add_action("save_post_post", [$this,'onUpadatePost'], 10, 3);
        }

        if($this->refresh_on_delete == '1'){
            add_action("before_delete_post", [$this,'ondeletePost'], 10, 1);
            add_action('transition_post_status', [$this,'onTrashedPost'], 10, 3);
        }
        
    }

    function onUpadatePost($postID,$post,$update){

        if ($post->post_status === 'trash') return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (wp_is_post_autosave($postID) || wp_is_post_revision($postID)) return;
        if ($post->post_status === 'auto-draft') return;

        $this->CLA_Posts->get_post_type($postID);

        if($this->CLA_Posts->get_post_type($postID) == 'post'){

            if($update && $this->refresh_on_update == '1'){
                $this->CLA_Cache_Handler->refresh_cache();
            }   

            if(!$update && $this->refresh_on_publish == '1'){
                $this->CLA_Cache_Handler->refresh_cache();
            }
            
        }

    }

    function onSceduledPostPublished($postID){

        $this->CLA_Posts->get_post_type($postID);

        if($this->CLA_Posts->get_post_type($postID) == 'post'){

            if($this->refresh_on_publish == '1'){
                $this->CLA_Cache_Handler->refresh_cache();
            }
        }

    }

    function ondeletePost($postID){

        $this->CLA_Posts->get_post_type($postID);

        if($this->CLA_Posts->get_post_type($postID) == 'post'){  
            
            $this->CLA_Cache_Handler->refresh_cache();

        }
        
    }

    function onTrashedPost($new_status, $old_status, $post){

        if ($new_status === 'trash' && $old_status !== 'trash') {
        
            if($this->CLA_Posts->get_post_type($post->ID) == 'post'){
                
                $this->CLA_Cache_Handler->refresh_cache();
                
            }
        }
        
    }

}