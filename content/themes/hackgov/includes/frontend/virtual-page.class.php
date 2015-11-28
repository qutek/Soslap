<?php

/**
 * FunkmoVirtualPage Class.
 *
 * @class       FunkmoVirtualPage
 * @version     1.0
 * @author lafif <lafif@astahdziq.in>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (!class_exists('FunkmoVirtualPage')){

    class FunkmoVirtualPage {

        private $slug ='';
        private $title ='';
        private $content = '';
        private $template = '';

        /**
         * __construct
         * @param array $arg post to create on the fly
         * @author Ohad Raz 
         * 
         */
        function __construct( $args=array() ){
            $this->slug = (isset($args['slug'])) ? $args['slug'] : '';
            $this->title = (isset($args['title'])) ? $args['title'] : '';
            $this->content = (isset($args['content'])) ? $args['content'] : '';
            $this->template = (isset($args['template'])) ? $args['template'] : '';

            add_filter( 'the_posts',array($this,'virtual_page'));
            add_filter( 'template_include', array($this, 'template_used'), 10, 1 );
        }

        /**
         * virtual_page 
         * the Money function that catches the request and returns the page as if it was retrieved from the database
         * @param  array $posts 
         * @return array 
         * @author Ohad Raz
         */
        public function virtual_page($posts){
            global $wp, $wp_query, $virtual_page; // used to stop double loading
            
            if ( !$virtual_page && (strtolower($wp->request) == $this->slug || $wp->query_vars['page_id'] == $this->slug) ) {
                // stop interferring with other $posts arrays on this page (only works if the sidebar is rendered *after* the main page)
                $virtual_page = true;
                
                // create a fake virtual page
                $post = new stdClass;
                $post->post_author = 1;
                $post->post_name = $this->slug;
                $post->guid = get_bloginfo('wpurl') . '/' . $this->slug;
                $post->post_title = $this->title;
                $post->post_content = $this->content;
                $post->ID = -999;
                $post->post_type = 'page';
                $post->post_status = 'static';
                $post->comment_status = 'closed';
                $post->ping_status = 'open';
                $post->comment_count = 0;
                $post->post_date = current_time('mysql');
                $post->post_date_gmt = current_time('mysql', 1);
                $posts=NULL;
                $posts[]=$post;
                
                // make wpQuery believe this is a real page too
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->is_home = false;
                $wp_query->is_archive = false;
                $wp_query->is_category = false;
                unset($wp_query->query["error"]);
                $wp_query->query_vars["error"]="";
                $wp_query->is_404=false;
            }
            
            return $posts;
        }

        public function template_used($template){
            global $wp_query;

            if(!empty($this->template) && (get_query_var( 'name' ) == $this->slug ) ){
                return $this->template;
            }

            return $template;
        }

    }//end class
}//end if 


// $args = array(
//         'slug' => 'konfirmasi',
//         'title' => 'Fake Page Title',
//         'content' => 'This is the fake page content'
// );
// new FunkmoVirtualPage($args);