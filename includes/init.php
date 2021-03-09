<?php

// Vendor

use rybkinevg\trunov\Transfer;

require(dirname(__FILE__) . '/vendor/Kama_Post_Meta_Box.php');
require(dirname(__FILE__) . '/vendor/taxonomy-thumb.php');

// Transfer
require(dirname(__FILE__) . '/transfer/Transfer.php');
require(dirname(__FILE__) . '/transfer/Books.php');
require(dirname(__FILE__) . '/transfer/Court.php');
require(dirname(__FILE__) . '/transfer/For_lawyer.php');
require(dirname(__FILE__) . '/transfer/Lawyers.php');
require(dirname(__FILE__) . '/transfer/Partners.php');
require(dirname(__FILE__) . '/transfer/Posts.php');
require(dirname(__FILE__) . '/transfer/Media_columns.php');
require(dirname(__FILE__) . '/transfer/Services.php');
require(dirname(__FILE__) . '/transfer/Works.php');

// Post types
require(dirname(__FILE__) . '/inc/post-type-post.php');
require(dirname(__FILE__) . '/inc/post-type-lawyers.php');
require(dirname(__FILE__) . '/inc/post-type-books.php');
require(dirname(__FILE__) . '/inc/post-type-works.php');
require(dirname(__FILE__) . '/inc/post-type-court.php');
require(dirname(__FILE__) . '/inc/post-type-partners.php');
require(dirname(__FILE__) . '/inc/post-type-media-columns.php');
require(dirname(__FILE__) . '/inc/post-type-for-lawyer.php');
require(dirname(__FILE__) . '/inc/post-type-services.php');

// Includes
require(dirname(__FILE__) . '/inc/meta-box-custom.php');
require(dirname(__FILE__) . '/inc/default-permalinks-structure.php');
require(dirname(__FILE__) . '/inc/disable-gutenberg.php');

Transfer::init();
