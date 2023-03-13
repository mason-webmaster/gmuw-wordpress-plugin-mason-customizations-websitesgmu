<?php

/**
 * custom php code related to the admin post editor
 */


//Modify the editor page HTML body clases
add_filter('admin_body_class', 'gmuw_websitesgmu_admin_editor_classes');
function gmuw_websitesgmu_admin_editor_classes($classes) {

    // Get global variables
    global $pagenow;
    global $post;

    //Is this the edit or new post page
    if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
        
        //Is this post deleted?
        if (get_field('deleted', $post->ID)==1) {

            //Add deleted class
            $classes .= ' post-deleted ';

        }
        
        //Is this post marked for follow-up?
        if (get_field('follow_up', $post->ID)==1) {

            //Add follow-up class
            $classes .= ' post-follow-up ';

        }

    }
 
    return $classes;
}