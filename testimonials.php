<?php 
/*
	Plugin Name: Testimonials
	Description: Show off, a little, and display testimonials from users, patrons, listeners, or clients. 
	Author: Michael Schofield
	Author URI: http://www.michael-schofield.com
	Version: 1.0
*/

/* ==========================
 * "Testimonial" Post-Type */
function testimonials_init() {
	$args = array(

		'public' => true,
		'label' => 'Testimonials',
		'supports' => array(

			'title',
			'editor',
			'custom-fields'

			)
		);

	register_post_type('testimonials', $args);

}

add_action('init', 'testimonials_init');

/* ==========================
 * Client's Name and Handle */
function set_testimonial_custom_fields($post_id) {

    if ( $_GET['post_type'] == 'testimonials' ) {
 
        add_post_meta($post_id, 'Client Name', '', true);
        add_post_meta($post_id, 'Link', '', true);
 
    }

    return true;
}
 
add_action('wp_insert_post', 'set_testimonial_custom_fields');

/* ==========================
 * Optional Carousel Script 
function testimonials_register_scripts() {

    if (!is_admin()) {

        // Register Scripts ...
        wp_register_script('testimonials_slide_js', plugins_url('js/slides.min.jquery.js', __FILE__), array('jquery') );
        wp_register_script('testimonials_script', plugins_url('js/script.js', __FILE__), array('jquery') );
 
        // and then Enqueue THEM
        wp_enqueue_script('testimonials_slide_js');
        wp_enqueue_script('testimonials_script');
    }
}
 
add_action('wp_print_scripts', 'testimonials_register_scripts');
*/

/* ==========================
 * Let's Generate the HTML */
function display_testimonial() {
 
    $args = array(
        'post_type' => 'testimonials', //We'll grab the testimonial.
        'posts_per_page' => 1, //For now, I want to display just one - at random.
        'orderby' => 'rand'
    );
 
    /* =========================================
     * Un-comment to enable optional carousel */
    //$result .='<div id="slides testimonials"><div class="slides_container">';

 
    $the_query = new WP_Query($args);
 
    while ( $the_query->have_posts() ) : $the_query->the_post();
 
        $client_name_value =get_post_meta(get_the_ID(), 'Client Name', true);
        $link_value = get_post_meta(get_the_ID(), 'Link', true);
 
        /* =========================================
         * Un-comment to enable optional carousel */
        //$result .='<div class="testimonial">';
 
        $result .= '<div class="testimonial">'.get_the_content().'</div>';
         
        if ($link_value != '') {
            $result .= '<div class="testimonial-author"><a href="http://'.$link_value.'" >'.$client_name_value.'</a></div>';
        }
        else {
            $result .= '<div class="testimonial-author">'.$client_name_value.'</div>';
        }
 
        $result .='</div>';
 
    endwhile;
 
    /* =========================================
     * Un-comment to enable optional carousel */
    //$result .= '</div></div>';
 
    return $result;
}

// Let's make this available as a short code.
add_shortcode('testimonials', 'display_testimonial');

?>