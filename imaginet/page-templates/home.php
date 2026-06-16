<?php
/**
 * Template Name: Home Page 
 *
 * This is the template that displays the homepage layout.
 */

get_header(); ?>

<main id="primary-content" <?php post_class('page-wrap homepage-wrapper'); ?>>
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>

                <?php the_content(); ?>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>   
</main>

<?php get_footer(); ?>
