<?php //Template Name: Privacy Policy  ?>

<?php get_header(); ?>

<main id="primary-content">
    <section id="hero">
        <div class="container">
            <h1>
                <?php the_title(); ?>
            </h1>
        </div>
    </section>
    <section class="page-main">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>

                    <?php the_content(); ?>

                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
