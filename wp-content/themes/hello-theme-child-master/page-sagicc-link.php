<?php
/*
Template Name: Sagicc Link Generator
*/
get_header(); // Esto carga el <head>, <body> y los estilos del theme
?>

<div class="sagicc-link-wrapper">
    <?php
    $custom_page_path = get_stylesheet_directory() . '/custom-pages/sagicc-link/content.php';
    if (file_exists($custom_page_path)) {
        include($custom_page_path);
    } else {
        echo "<p>Custom content not found.</p>";
    }
    ?>
</div>

<?php get_footer(); ?>
