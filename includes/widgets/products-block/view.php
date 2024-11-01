<?php
$per_page = $settings['number_product']['size'];
$cat = $settings['cat_query'];
$id = $settings['id_query'];

if($settings['query_type'] == 'category'){
    $query_args = array(
        'post_type' => 'product',
        'posts_per_page' => $per_page,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $cat,
            ) ,
        ) ,
    );
}

if($settings['query_type'] == 'individual'){
    $query_args = array(
        'post_type' => 'product',
        'posts_per_page' => $per_page,
        'post__in' => $id,
        'orderby' => 'post__in'
    );
}

$wp_query = new \WP_Query($query_args);
?>

<section class="sf-products-block">
    <?php if ($wp_query->have_posts()) {
        while ($wp_query->have_posts()) {
            $wp_query->the_post();
            ?>
                    <h3><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
            <?php
        }
        wp_reset_postdata();
    } wp_reset_query();
    ?>
</section>