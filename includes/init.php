<?php

use rybkinevg\trunov\Transfer;

// Vendor
require(dirname(__FILE__) . '/vendor/taxonomy-thumb.php');

// Carbon Fields
require(dirname(__FILE__) . '/carbon-fields/carbon-fields.php');

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
require(dirname(__FILE__) . '/transfer/SOS.php');

// Post types
require(dirname(__FILE__) . '/theme/post-type-post.php');
require(dirname(__FILE__) . '/theme/post-type-lawyers.php');
require(dirname(__FILE__) . '/theme/post-type-books.php');
require(dirname(__FILE__) . '/theme/post-type-works.php');
require(dirname(__FILE__) . '/theme/post-type-court.php');
require(dirname(__FILE__) . '/theme/post-type-partners.php');
require(dirname(__FILE__) . '/theme/post-type-media-columns.php');
require(dirname(__FILE__) . '/theme/post-type-for-lawyer.php');
require(dirname(__FILE__) . '/theme/post-type-services.php');
require(dirname(__FILE__) . '/theme/post-type-sos.php');

// Includes
require(dirname(__FILE__) . '/theme/meta-box-custom.php');
require(dirname(__FILE__) . '/theme/default-permalinks-structure.php');
require(dirname(__FILE__) . '/theme/disable-gutenberg.php');

Transfer::init();
