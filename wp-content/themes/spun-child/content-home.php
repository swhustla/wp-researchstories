<?php
/**
 * @package Spun
 * @since Spun 1.0
 */

/*
 * Get the post thumbnail; if one does not exist, try to get the first attached image.
 * If no images exist, let's print the post title instead.
 */

global $post;
$postclass = '';

if ( '' != get_the_post_thumbnail() ) {
        $thumb = get_the_post_thumbnail( $post->ID, 'post-thumbnail', array( 'title' => esc_attr( get_the_title() ) ) );
        $title = '<span class="hometitle">' . esc_attr( get_the_title()) . '</span>';
}
else {
        $args = array(
                                'post_type' => 'attachment',
                                'numberposts' => 1,
                                'post_status' => null,
                                'post_mime_type' => 'image',
                                'post_parent' => $post->ID,
                        );


        $first_attachment = get_children( $args );

        if ( $first_attachment ) {
                foreach ( $first_attachment as $attachment ) {
                        $thumb = wp_get_attachment_image( $attachment->ID, 'post-thumbnail', false, array( 'title' => esc_attr( get_the_title() ) ) );
                }
                $title = '<span class="hometitle">' . esc_attr( get_the_title()) . '</span>';
        }
        else {
                $thumb = '<span class="no-thumbnail">' . get_the_title() . '</span>';
                $postclass = 'no-thumbnail';
                $title = '';
        }
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $postclass ); ?>>
        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
              <?php echo $thumb; ?>
              <?php echo $title; ?>
         </a>
</article><!-- #post-<?php the_ID(); ?> -->
