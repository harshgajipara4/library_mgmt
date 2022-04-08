<?php get_header(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>library search</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>
<body>



<div class="container">

  <div class="col-sm-4">
  <h2>Book search</h2>
  <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
    <div class="form-group">
      <label for="bookname">Bookname:</label>
      <input type="text" class="form-control" id="bookname" placeholder="Book name" name="bookname">
    </div>
    <div class="form-group">
      <label for="bookname">Author:</label>
      <input type="text" class="form-control" id="author" placeholder="Author" name="author">
    </div>
    
      <div class="form-group">
<label for="bookname">Author:</label>
  
    <?php
    /** The taxonomy we want to parse */
    $taxonomy = "author";
    /** Get all taxonomy terms */
    $terms = get_terms($taxonomy, array(
            "orderby"    => "count",
            "hide_empty" => false
        )
    );
    /** Get terms that have children */
    $hierarchy = _get_term_hierarchy($taxonomy);
    ?>
    <select name="terms" id="terms">
        <?php
            /** Loop through every term */
            foreach($terms as $term) {
                /** Skip term if it has children */
                if($term->parent) {
                    continue;
                }
                echo '<option value="' . $term->name . '">' . $term->name . '</option>';
                /** If the term has children... */
                if($hierarchy[$term->term_id]) {
                    /** ...display them */
                    foreach($hierarchy[$term->term_id] as $child) {
                        /** Get the term object by its ID */
                        $child = get_term($child, "category");
                        echo '<option value="' . $term->name . '"> - ' . $child->name . '</option>';
                    }
                }
            }
        ?>
    </select>

</div>

  <div class="form-group">

    <label for="bookname">Publisher:</label>
    <?php
    /** The taxonomy we want to parse */
    $taxonomy = "publisher";
    /** Get all taxonomy terms */
    $terms = get_terms($taxonomy, array(
            "orderby"    => "count",
            "hide_empty" => false
        )
    );
    /** Get terms that have children */
    $hierarchy = _get_term_hierarchy($taxonomy);
    ?>
    <select name="terms" id="terms">
        <?php
            /** Loop through every term */
            foreach($terms as $term) {
                /** Skip term if it has children */
                if($term->parent) {
                    continue;
                }
                echo '<option value="' . $term->name . '">' . $term->name . '</option>';
                /** If the term has children... */
                if($hierarchy[$term->term_id]) {
                    /** ...display them */
                    foreach($hierarchy[$term->term_id] as $child) {
                        /** Get the term object by its ID */
                        $child = get_term($child, "category");
                        echo '<option value="' . $term->name . '"> - ' . $child->name . '</option>';
                    }
                }
            }
        ?>
    </select>
</div>

<div class="form-group">
      <label for="bookname">Rating:</label>
      <input type="text" class="form-control" id="rating" placeholder="Rating" name="rating">
    </div>



    <button class="btn btn-default">Search</button>
    <input type="hidden" name="action" value="myfilter">
  </form>
  </div>
</div>




<div class="container">
  <div class="col-lg-4">          
  <table class="table table-bordered main-table">
    <thead>
      <tr>
        <th>No.</th>
        <th>Book name</th>
        <th>Price</th>
        <th>Author</th>
        <th>Publisher</th>
        <th>Rating</th>
      </tr>
    </thead>

    <?php
    $i=1;
                $books = new WP_Query(array(
                  'post_type'=>'book',
                  'posts_per_page'=>-1,
                ));

       
                while($books->have_posts()){
                  $books->the_post();

$book_price = get_post_meta( get_the_ID(), 'price', true);
$book_rating = get_post_meta( get_the_ID(), 'rating', true);
?>

    <tbody>
      <tr>
        <td><?php echo $i; ?></td>
        <td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
        <td><?php echo $book_price; ?></td>
        <td><?php $terms = get_the_terms( $post->ID, 'author' );
if ( !empty( $terms ) ){
    // get the first term
    $term = array_shift( $terms );
    echo $term->name;
} ?> </td>
        <td><?php $terms = get_the_terms( $post->ID, 'publisher' );
if ( !empty( $terms ) ){
    // get the first term
    $term = array_shift( $terms );
    echo $term->name;
} ?></td>
        <td><?php echo $book_rating; ?></td>
      </tr>


    <?php $i++; } ?>
      
    </tbody>
  </table>
  </div>
</div>


<div class="col-lg-4">
<div id="response"></div>
</div>

<?php get_footer(); ?>
</body>
</html>
