<?php

function find_tweets($content, $shorturl){
	$content_stripped = strip_tags($content);
	$url_len = strlen($shorturl);
	$tweet_content_len = 140-$url_len-1;
	$content_array = explode(". ", $content_stripped);

	$tweet_array = array();
	foreach ($content_array as $possible_tweet){

		$possible_tweet_len = strlen($possible_tweet);
		
		if ($possible_tweet_len<$tweet_content_len && !empty($possible_tweet)){

			$tweet_array[] = $possible_tweet. " ".$shorturl ;
		}
	}

	return $tweet_array;
	unset($tweet_array);
}


global $wpdb;
global $post;
$dir = plugin_dir_path( __FILE__ );

$pagenum = filter_input(INPUT_GET, 'pagenum') ? absint(filter_input(INPUT_GET, 'pagenum')) : 1;

$args = array(
    'post_type' => 'post',
    'orderby' => 'ASC',
    'posts_per_page'=>5,    //-1
	'post_status' =>'publish',
	'paged'          => $pagenum
);

// The Query
$the_query = new WP_Query( $args );

// The Loop
?>
<table class="wp-list-table widefat striped">
	<thead>
    	<tr>
        	<th>Post</th>
            <th>Tweets</th>
        </tr>
    </thead>
<?
if ( $the_query->have_posts() ) {

	while ( $the_query->have_posts() ) {

		 
		$the_query->the_post();
		$title = get_the_title();
		$permalink = get_the_permalink();
		echo '<tr><td><a href="'.$permalink.'">'.$title.'</a></td>';
		$find_tweets_array = find_tweets(get_the_content(),wp_get_shortlink());
		echo '<td>';
		foreach ($find_tweets_array as $find_tweets){
				 echo "<div style='height: 35px; display: inline-table; width: 100%;'>";
				 echo $find_tweets;
				 echo "<button style='float:right' class='copy-button' data-clipboard-text='".$find_tweets."' title='Click to copy me.'>Copy to Clipboard</button>"; 
				 echo "<a onclick=\"window.open('https://twitter.com/intent/tweet?text=$find_tweets', 'newwindow', 'width=500, height=250'); return false;\"><button style='float:right; margin-right:1em;' class='tweet-button'>Tweet</button></a>";
				 echo "</div>";	
		}
		echo '</td></tr>';
	}

} else {
	// no posts found
}
?>

</table>
<?
//pagination
$count_posts = wp_count_posts();

$limit = 5; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$total = $count_posts->publish;
$num_of_pages = ceil( $total / $limit );

$page_links = paginate_links( array(
    'base' => add_query_arg( 'pagenum', '%#%' ),
    'format' => '',
    'prev_text' => __( '&laquo;', 'text-domain' ),
    'next_text' => __( '&raquo;', 'text-domain' ),
    'total' => $num_of_pages,
    'current' => $pagenum
) );

if($page_links){
    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
?>
<?
		wp_enqueue_script( 'ZeroClipboard');
		wp_enqueue_script( 'Zeromain');
 
?>