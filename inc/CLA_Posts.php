<?php

class CLA_Posts{

    private $number_of_posts;

    public function __construct($number_of_posts) {

        if(!is_numeric($number_of_posts) || $number_of_posts <= 0) {
            $number_of_posts = 5; 
        }
        $this->number_of_posts = $number_of_posts;
        
    }

    function get_posts(){

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $this->number_of_posts,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            foreach ($query->posts as &$post) {
                $post->permalink = get_permalink($post->ID);
                $post->image_url = get_the_post_thumbnail_url($post->ID, 'full');
            }
            return $query->posts;
        }
        return false;

    }

    function get_post_type($postID){

        $post = get_post($postID);
        if($post){
            return $post->post_type;
        }else{
            return false;
        }

    }

    function set_number_of_posts($number_of_posts){

        if(!is_numeric($number_of_posts) || $number_of_posts <= 0) {
            $number_of_posts = 5; 
        }
        $this->number_of_posts = $number_of_posts;

    }

    function get_number_of_posts(){
        return $this->number_of_posts;
    }

}