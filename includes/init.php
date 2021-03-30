<?php

use rybkinevg\trunov\Transfer;

// Vendor
require(dirname(__FILE__) . '/vendor/taxonomy-thumb.php');
require(dirname(__FILE__) . '/vendor/kama_excerpt.php');

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
require(dirname(__FILE__) . '/transfer/Certificates.php');
require(dirname(__FILE__) . '/transfer/Vacancies.php');
require(dirname(__FILE__) . '/transfer/Images.php');

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
require(dirname(__FILE__) . '/theme/post-type-certificates.php');
require(dirname(__FILE__) . '/theme/post-type-vacancies.php');
require(dirname(__FILE__) . '/theme/post-type-offices.php');
require(dirname(__FILE__) . '/theme/enqueue.php');

require(dirname(__FILE__) . '/theme/showmore.php');
require(dirname(__FILE__) . '/theme/trunov-get-post-taxes.php');
require(dirname(__FILE__) . '/theme/trunov-get-post-meta.php');
require(dirname(__FILE__) . '/theme/trunov-show-post-meta.php');
require(dirname(__FILE__) . '/theme/change-templates-for-static-main-page.php');
require(dirname(__FILE__) . '/theme/trunov-archive-page-title.php');
require(dirname(__FILE__) . '/theme/trunov-thumbnails.php');
require(dirname(__FILE__) . '/theme/trunov-posts-filter.php');
require(dirname(__FILE__) . '/theme/trunov-get-share-url.php');

// Includes
require(dirname(__FILE__) . '/theme/meta-box-custom.php');
require(dirname(__FILE__) . '/theme/default-permalinks-structure.php');
require(dirname(__FILE__) . '/theme/disable-gutenberg.php');

Transfer::init();
