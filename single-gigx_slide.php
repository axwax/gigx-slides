<?php
/*
File Description: Default Index Page
Built By: GIGX
Theme Version: 0.5
*/
 
get_header();
?>




<?php
/*
File Description: The Loop
Built By: GIGX
Theme Version: 0.5
*/
?>
  	<?php wp_nav_menu( array( 'theme_location' => 'above-posts', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>
      
    <div class="posts">

        <?php /* Do we have posts, then start the loop, otherwise display 404 */ ?>
      	<?php if (have_posts()) : ?>
          <?php /* Start the Loop */ ?>  	
      		<?php while (have_posts()) : the_post(); ?>
 		
        			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                
                <?php
                $img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_ID()),'gigx-slide',false);
  			        

                echo '<div class="wp-caption alignleft" style="width: 310px"><img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$p->post_title.'"/><p class="wp-caption-text">'.get_the_title().'</p></div>';
                echo '<h1 class="post-title">'.get_the_title().'</h1>';
                echo '<span class="postdate">Posted on '. get_the_time('jS F Y') .'</span>'; ?> 
                <div class="entry" style="padding-top:10px;">
                  <?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
        				</div>

              <?php if(defined ('CUSTOM_POST_TYPE') && is_singular(CUSTOM_POST_TYPE)) {
                //echo CUSTOM_POST_TYPE;
                  get_template_part(CUSTOM_POST_TYPE);
                }
              ?>
        				
        		<!--		<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted by <?php if (function_exists('author_exposed')) {author_exposed(get_the_author_meta('display_name'));}   ?> in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>  -->
        			</div>
    
               <?php comments_template( '', true ); ?> 			
      		<?php endwhile; ?>
              
      	<?php else : ?>
      	
      		<h2 class="center">Not Found</h2>
      		<p class="center">Sorry, but you are looking for something that isn't here.</p>
      		<?php get_search_form(); ?>
      
      	<?php endif; ?>
    </div><!-- end of posts div -->




 

<?php get_footer(); ?>