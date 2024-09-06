=== Delete Post Meta ===
Contributors: h4l9k
Tags: delete-post-meta, remove-post-meta, delete-meta-data, meta-data
Tested up to: 6.6
Stable tag: 1.2.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Delete Post Meta based on meta key.

== Description ==

Delete Post Meta based on meta key. Wrapper and admin panel for [delete_metadata](https://developer.wordpress.org/reference/functions/delete_metadata/).

WARNING! Use this plugin with caution. It will delete all post meta based on a meta key.

## Filter Object Type

Add the following to your `functions.php` or equivalent send a different object type.

```php

add_filter(
    'delete_post_meta_key_object_type',
    function ( $object_type ){
        // Accepts 'post', 'comment', 'term', 'user', or any other object type with an associated meta table.
        return 'user';
    }
);

```

== Screenshots ==

1. Tools admin page to input the meta key.

== Changelog ==

= 1.2.0 =

* Tested compatibilty with WordPress 6.6.1.
* Fixed assets folder references.
* Adds the ability to select object type to delete meta data from users, terms, comments, and other custom defined meta tables.

= 1.1.2 =

* Tested compatibilty with WordPress 6.5.2.

= 1.1.1 =

* Minor updates and confirmation of WordPress compatibilty with 6.4.2.

= 1.1 =

* Improvements were added based on feedback from the WordPress Plugin Review Team.

= 1.0 =

* Initial version.
