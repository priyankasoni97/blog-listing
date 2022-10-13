/**
 * jQuery public custom script file.
 */
jQuery( document ).ready( function( $ ) {
	'use strict';

	// Localized variables.
	var ajaxurl = CF_Public_JS_Obj.ajaxurl;
	var is_blog = CF_Public_JS_Obj.is_blog_page;

	/**
	 * Check if a number is valid.
	 * 
	 * @param {number} data 
	 */
	function is_valid_number( data ) {

		return ( '' === data || undefined === data || isNaN( data ) || 0 === data ) ? -1 :1;
	}

	/**
	 * Check if a string is valid.
	 *
	 * @param {string} $data
	 */
	function is_valid_string( data ) {

		return ( '' === data || undefined === data || ! isNaN( data ) || 0 === data ) ? -1 : 1;
	}

	/**
	 * Check if a email is valid.
	 *
	 * @param {string} email
	 */
	function is_valid_email( email ) {
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		return ( ! regex.test( email ) ) ? -1 : 1;
	}

	/**
	 * Check if a website URL is valid.
	 *
	 * @param {string} email
	 */
	 function is_valid_url( url ) {
		var regex = /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/;

		return ( ! regex.test( url ) ) ? -1 : 1;
	}

	/**
	 * Block element.
	 *
	 * @param {string} element
	 */
	function block_element( element ) {
		element.addClass( 'non-clickable' );
	}

	/**
	 * Unblock element.
	 *
	 * @param {string} element
	 */
	function unblock_element( element ) {
		element.removeClass( 'non-clickable' );
	}

	if( 'yes' === is_blog ){
		get_filtered_posts();
	}

	$(document).on('click', '.cf_load_more', function(){
		var paged    = $(this).attr('data-paged');
		get_filtered_posts(paged,null,null);
	});

	$(document).on('click', '.cf_category', function(){
		var category     = $(this).data('term_id');
		var categorySlug = $(this).data('term_slug');
		const url = new URL(window.location);
		url.searchParams.set( 'action', 'search');
		url.searchParams.set( 'category', categorySlug);
		window.history.pushState({},'',url);
		$('.cf_category').each(function(){
			jQuery('.cf_category').removeClass('active');
		});
		jQuery(this).addClass('active');
		get_filtered_posts(null,category,null);
	});

	$(document).on('submit','.bl_searchForm',function(e){
		e.preventDefault();
		var text = $('.bl_search_text').val();
		const url = new URL(window.location);
		url.searchParams.set( 'action', 'search');
		url.searchParams.set( 'text', text);
		window.history.pushState({},'',url);
		get_filtered_posts(null,null,text);
	});

	$(document).on('click','.close_search_filter',function(){
		var remove_filter = jQuery(this).data('remove_filter');
		var params   = cf_get_query_variable();
		const url = new URL(window.location);
		url.searchParams.delete(remove_filter);
		window.history.pushState({},'',url);
		if ('category' === remove_filter) {
			$('.cf_category[data-term_slug="'+params.category+'"]').removeClass('active');
		}
		if($.inArray("category", params) == -1 || $.inArray("text", params) == -1){
			url.searchParams.delete('action');
			window.history.pushState({},'',url);
			jQuery('.bl_filters_applied').hide();
		}
		$('.bl_filter_'+remove_filter).hide();
		get_filtered_posts();
	});

	function get_filtered_posts(paged = null, category = null, search_text = null ){
		var params   = cf_get_query_variable();
		if( $.inArray('category',params) !== -1 ){
			category = $('.cf_category[data-term_slug="'+params.category+'"]').attr('data-term_id');
			$('.cf_category[data-term_slug="'+params.category+'"]').addClass('active');
			jQuery('.bl_filter_category .innerfiltertag span').text(params.category);
			jQuery('.bl_filters_applied').show();
			jQuery('.bl_filter_category').show();
		}
		if( $.inArray('text',params) !== -1 ){
			search_text = params.text;
			$('.bl_search_text').val(search_text);
			jQuery('.bl_filter_text .innerfiltertag span').text(params.text);
			jQuery('.bl_filters_applied').show();
			jQuery('.bl_filter_text').show();
		}
		var post_data = {
			paged      : paged,
			category   : category,
			searchtext : search_text,
			action     : 'fetch_posts',
		};
		$.ajax({
			dataType: 'json',
			url: ajaxurl,
			type: 'POST',
			data: post_data,
			beforeSend: function() {
				$('.bs_loader').show();
			},
			success: function( response ){
				if ( 'success' === response.data.code ) {
					if(paged !== null){
						$('.blog_items').append( response.data.html );
					}else{
						$('.blog_items').html( response.data.html );
					}
					if( 0 < response.data.paged ) {
						$('.cf_load_more').attr('data-paged',response.data.paged);
						$('.load_more_main').show();
					}else{
						$('.load_more_main').hide();
					}
				} else {
					$('.blog_items').html( response.data.message );
					$('.load_more_main').hide();
				}
			},
			complete: function() {
				$('.bs_loader').hide();
			}
		});
	}

	/**
	 * Get QUery string variable value.
	 */
	 function cf_get_query_variable(  ) {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}
} );
