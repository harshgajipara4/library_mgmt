<?php
/**
 * library functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package library
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function library_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on library, use a find and replace
		* to change 'library' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'library', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'library' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'library_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'library_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function library_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'library_content_width', 640 );
}
add_action( 'after_setup_theme', 'library_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function library_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'library' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'library' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'library_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function library_scripts() {
	wp_enqueue_style( 'library-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'library-style', 'rtl', 'replace' );

	wp_enqueue_script( 'library-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'library_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



// Our custom post type function
/*function create_posttype() {
  
    register_post_type( 'library',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Library' ),
                'singular_name' => __( 'Library' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'library'),
            'show_in_rest' => true,
  
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );*/


// Our custom post type function
function create_posttype() {
  
    register_post_type( 'book',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Books' ),
                'singular_name' => __( 'Book' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'book'),
            'show_in_rest' => true,
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
  
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );


//hook into the init action and call create_book_taxonomies when it fires
 
add_action( 'init', 'author_taxonomy', 0 );
 
//create a custom taxonomy name it subjects for your posts
 
function author_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
 
  $labels = array(
    'name' => _x( 'Authors', 'taxonomy general name' ),
    'singular_name' => _x( 'Author', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Authors' ),
    'all_items' => __( 'All Author' ),
    'parent_item' => __( 'Parent Author' ),
    'parent_item_colon' => __( 'Parent Author:' ),
    'edit_item' => __( 'Edit Author' ), 
    'update_item' => __( 'Update Author' ),
    'add_new_item' => __( 'Add New Author' ),
    'new_item_name' => __( 'New Author Name' ),
    'menu_name' => __( 'Authors' ),
  );    
 
// Now register the taxonomy
  register_taxonomy('author',array('book'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'author' ),
  ));
 
}




//hook into the init action and call create_book_taxonomies when it fires
 
add_action( 'init', 'publisher_taxonomy', 0 );
 
//create a custom taxonomy name it subjects for your posts
 
function publisher_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
 
  $labels = array(
    'name' => _x( 'Publisher', 'taxonomy general name' ),
    'singular_name' => _x( 'Publisher', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Publisher' ),
    'all_items' => __( 'All Publisher' ),
    'parent_item' => __( 'Parent Publisher' ),
    'parent_item_colon' => __( 'Parent Publisher:' ),
    'edit_item' => __( 'Edit Publisher' ), 
    'update_item' => __( 'Update Publisher' ),
    'add_new_item' => __( 'Add New Publisher' ),
    'new_item_name' => __( 'New Publisher Name' ),
    'menu_name' => __( 'Publisher' ),
  );    
 
// Now register the taxonomy
  register_taxonomy('publisher',array('book'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'publisher' ),
  ));
 
}


function add_ajax_scripts() {
    wp_enqueue_script( 'ajaxcalls', get_template_directory_uri() . '/js/ajax-calls.js', array(), '1.0.0', true );

    wp_localize_script( 'ajaxcalls', 'ajax_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'ajaxnonce' => wp_create_nonce( 'ajax_post_validation' )
    ) );
}

add_action( 'wp_enqueue_scripts', 'add_ajax_scripts' );



add_action('wp_ajax_myfilter', 'my_filter_function'); // wp_ajax_{ACTION HERE} 
add_action('wp_ajax_nopriv_myfilter', 'my_filter_function');

function my_filter_function(){


	if(  isset( $_POST['bookname'] )){

		$args = array(
		'numberposts'	=> -1,
		'post_type'		=> 'book',
		'name' => $_POST['bookname'] ,
	);	


	$query = new WP_Query( $args );
 	

 	$i=1;
	if( $query->have_posts() ) :
		while( $query->have_posts() ): $query->the_post(); ?>

			

			<div class="container">
  <div class="col-lg-4">          
  <table class="table table-bordered">
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



      
    </tbody>
  </table>
  </div>
</div>





			<?php $i++;	endwhile; 


			else :
		echo 'No Match found';

	endif;
		wp_reset_postdata(); 

die();
 	} 

}


