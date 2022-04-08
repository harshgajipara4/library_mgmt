<?php get_header(); ?>

<h2><?php the_title(); ?></h2>

By 

<?php $terms = get_the_terms( $post->ID, 'author' );
if ( !empty( $terms ) ){
    // get the first term
    $term = array_shift( $terms );
    
} ?> 


<h4><a href="<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></h4>


<?php $terms2 = get_the_terms( $post->ID, 'publisher' );
if ( !empty( $terms2 ) ){
    // get the first term
    $term = array_shift( $terms2 );
    
} ?>

<h4><a href="<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></h4> 



<p><?php the_content(); ?></p>

<?php 
$book_price = get_post_meta( get_the_ID(), 'price', true);
$book_rating = get_post_meta( get_the_ID(), 'rating', true);
 ?>

 <p>Price : <?php echo $book_price; ?></p>
 <p>Rating : <?php echo $book_rating; ?></p>





<?php get_footer(); ?>