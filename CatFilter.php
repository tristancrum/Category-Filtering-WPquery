<?php 

add_shortcode( 'catfill', 'catfill'); 

?>

<?php function catfill(){ ob_start(); ?>

<?php $categories = get_categories(); ?>
<style>
.cat-list {
    display: flex;
    flex-direction: row;
    align-content:center;
    justify-content: center;
}

.cat-list li {
    list-style-type: none;
    padding-left: 10px;
    padding-right: 10px;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 16px;
    border: 4px solid;
    margin-left: 5px;
    margin-right: 5px;
}

.cat-list a {
    font-family: 'Arial' !important;
	color: #2f3a45;
}
	.practicescont {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-gap: 20px;
}

.indiareas {
    background-color: #f5f5f5;
    padding-top:  15px;
    padding-bottom: 15px;
    padding-left: 10px;
    padding-right: 10px;
}
</style>
<?php 
  $practiceareas = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => -1,
    'order_by' => 'date',
    'order' => 'desc',
  ]);
?>

<ul class="cat-list">
  <li><a class="cat-list_item active" href="#!" data-slug="">All</a></li>

  <?php foreach($categories as $category) : ?>
    <li>
      <a class="cat-list_item" href="#!" data-slug="<?= $category->slug; ?>">
        <?= $category->name; ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>

<?php if($practiceareas->have_posts()): ?>
<div class="practicescont">
  
    <?php
      while($practiceareas->have_posts()) : $practiceareas->the_post(); ?>
	<div class="indiareas">
	 <h4><?php the_title(); ?></h4>
		<p>
			Lorem Ipsum Dorm Kasko Flacko Sheshivo tandem...
		</p>
	</div>
	  <?php
      endwhile;
    ?>
</div>
  <?php wp_reset_postdata(); ?>
<?php endif; ?>
<script>
$('.cat-list_item').on('click', function() {
  $('.cat-list_item').removeClass('active');
  $(this).addClass('active');

  $.ajax({
    type: 'POST',
    url: '/wp-admin/admin-ajax.php',
    dataType: 'html',
    data: {
      action: 'filter_practices',
      category: $(this).data('slug'),
    },
    success: function(res) {
      $('.practicescont').html(res);
    }
  })
});
</script>

<?php return ob_get_clean();
    wp_reset_postdata(); 
};

function filter_practices() {
  $catSlug = $_POST['category'];

  $ajaxposts = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => -1,
    'category_name' => $catSlug,
    'orderby' => 'menu_order', 
    'order' => 'desc',
  ]);
  $response = '';

  if($ajaxposts->have_posts()) {
    while($ajaxposts->have_posts()) : $ajaxposts->the_post();?>
      <div class="indiareas">
	 <h4><?php the_title(); ?></h4>
		<p>
			Lorem Ipsum Dorm Kasko Flacko Sheshivo tandem...
		</p>
	</div>
    <?php endwhile;
  } else {
    $response = 'empty';
  }

  echo $response;
  exit;
}
add_action('wp_ajax_filter_practices', 'filter_practices');
add_action('wp_ajax_nopriv_filter_practices', 'filter_practices');
