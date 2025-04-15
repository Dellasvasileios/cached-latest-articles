<?php

require_once plugin_dir_path(__FILE__) . 'CLA_Posts.php';
require_once plugin_dir_path(__FILE__) . 'CLA_File_Manager.php';


class CLA_Cache_Handler{

    private $ID;

    private $cache_dir;

    private $CLA_Posts;
    private $CLA_File_Manager;

    function __construct($id,$CLA_Posts, $CLA_File_Manager, $cache_dir){

        $this->ID = $id;

        $this->cache_dir = $cache_dir;

        $this->CLA_Posts = $CLA_Posts;
        $this->CLA_File_Manager = $CLA_File_Manager;
        
    }

    function refresh_cache(){
        $this->create_cache_file();
    }

    function create_cache_file(){

        $posts = $this->CLA_Posts->get_posts();
          
        if($posts){
            $this->CLA_File_Manager->update_file($this->cache_dir . 'articles-cache'.$this->ID.'.json', $posts);
        }else{
            $this->CLA_File_Manager->update_file($this->cache_dir .'articles-cache'.$this->ID.'.json', []);
        }
        
    }

}