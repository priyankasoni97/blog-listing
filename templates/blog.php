<?php
/**
 * Blog page Template.
 *
 * @package Core_Functions
 */

get_header();
$categories = get_terms(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
	)
);
?>
<div class="blog_page_main">
	<div class="cms-filter-tag bl_filters_applied" style="display:none">
		<label>Filter :</label>	
		<ul>
			<li class="bl_filter_text" style="display:none">
				<div class="innerfiltertag">
					Search:&nbsp;<span></span> <i class="fas fa-times-circle close_search_filter" data-remove_filter="text"></i>
				</div>	
			</li>	
			<li class="bl_filter_category" style="display:none">
				<div class="innerfiltertag">
					Category:&nbsp;<span></span> <i class="fas fa-times-circle close_search_filter" data-remove_filter="category"></i>
				</div>	
			</li>
		</ul>	
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-12 col-12">
			<div class="sidebar-widget-area">
				<div class="widget search-widget">
					<h5 class="widget-title">Search blogs</h5>
					<form class="searchForm bl_searchForm" method="post">
					<div class="form_group">
						<input type="text" class="form_control bl_search_text" placeholder=" Start typing here to search..." name="text" value="">
						<button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
					</div>
					</form>
				</div>
				<div class="blog_categories">
					<div class="widget category-widget">
						<h5 class="widget-title">Categories</h5>
						<ul class="category-nav bl_category_nav">
						<?php
						foreach ( $categories as $category ) {
							$class = 'bl_parent_term';
							if ( $category->parent > 0 ) {
								$class = 'bl_child_term';
							}
							?>
							<li class="<?php echo esc_attr( $class ); ?>">
								<a href="javascript:void(0);" class="cf_category" data-term_id="<?php echo esc_attr( $category->term_id ); ?>" data-term_slug="<?php echo esc_attr( $category->slug ); ?>"> 
									<i class="fa-solid fa-angle-right"></i>
									<?php echo esc_html( $category->name ); ?> 
								</a>
							</li>
							<?php
						}
						?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-12 col-12">
			<div class="blog_page_heading">
				<h3>Blogs</h3>
			</div>
			<div class="blog_page_items mt-4">
				<div class="bs_loader"></div>
				<div class="row blog_items"></div>
			</div>
			<div class="row text-center load_more_main" style="display:none;">
				<div class="col-md-12">
					<button class="cf_load_more">Load More</button>
				</div>
			</div>
		</div>
	</div>	
</div>
<?php
get_footer();
