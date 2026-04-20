### WooCommerce Orphan Images

This simple plugin allow you to quickly and easily find images that are not used in products and delete them.

## Description

This plugin adds a button to the Media Library list vew that runs a custom SQL query. This query populates a custom database table with all the attachment ids that are not being used in any product as main image or media gallery image.

There is also an option to ignore certain images that are used in pages, posts etc by adding them to another custom table.

Finally, the plugin adds 2 options to the attachment filter dropdown. The first allows you to see the images that are considered orphans and the second one allow you to see the images that you have ignored.

## Installation

1. Upload plugin files to the `/wp-content/plugins/wc-orphan-images` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit your Media Library and click the `Update ohpan images table` button to populate the table
1. Choose the `Orphan Images` dropdown uption to see the images that are considered orphans. You can choose to delete or ignore them
1. Choose the `Ignored images` dropdown option to see the images that have been ignored. You can un-ignore them if you wish

## Frequently Asked Questions

# Does this scan for images inside pages/posts/product descriptions etc?

No, this only scans for images that are used as main images or media gallery images.

## Changelog

# 1.0
* Initial release