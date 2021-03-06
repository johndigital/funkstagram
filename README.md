```
Funkstagram: A Wordpress plugin by Funkhaus
      ___ _   _ _  _ _  __
     | __| | | | \| | |/ /
     | _|| |_| | .` | ' <
     |_| _\___/|_|\_|_|\_\
     | || | /_\| | | / __|
     | __ |/ _ \ |_| \__ \
     |_||_/_/ \_\___/|___/

```

### Summary:

Funkstagram is a simple Instagram image importer for Wordpress. Pick users and/or
tags and automatically load images into the Wordpress media library.

### Features:

* Import images for multiple users
* Filter user images by tag
* Custom post status (Draft|Pending-Review|Published) for each image
* Import all Instagram images for a specific tag
* Attach all imported images to a specific page
* Runs automatically every 10 minutes, no cron job needed

### Instructions for use:

1. Download Funkstagram Master and unzip
2. Move entire folder into plugins folder in Wordpress install, and activate through the admin interface
3. Navigate to settings page at `Tools > Funkstagram`
4. Put in Instagram API key. If you don't have one, register a new client at: http://instagram.com/developer/register/
5. Select a page from the drop-down to attach all imported images to. If no page selected, photos will go straight to the media library with no parent
6. If you plan to use the image status feature, set a default status for the images to import as.</br>
  * _**Note:** This won't change anything unless your theme is setup to use this feature. The status feature is particularly useful when you don't have full control over what images are being imported (i.e. importing all images tagged `#awesome`.) Status gives you an extra layer so images can be approved in the backend before showing up on the front._
7. If importing by user, add usernames **or** user IDs into the `Users to import` field.
8. Enter any tags into the `Filter by tags` field. If importing by user, only images with these tags from those particular users will be imported. If no users are specified, all images posted to Instagram with those particular tags will be imported. </br>
  * _**Note:** If importing by tag only and the tag is not specific enough, you will end up with a lot of images being imported_
9. Click save changes and then click the `Import Now` button to test importing. This may take a while if importing a large number of photos.
10. When Import Now finishes, a log will appear at the bottom of the page. If 0 errors were returned, and images were successfully imported then check the `enable auto-import` box at the top of the screen and save changes. Images will now be imported automatically. </br>
  * _**Note:** If you would rather use a UNIX Cron to run the import rather than a Wordpress cron, use this URL format: `http://example.com/wp-admin/admin-ajax.php?action=funkstagram_import`_

### Videos:
When Funkstagram encounters a video post, it will automatically import the thumbnail of the video into the media library just as it does with all photo posts. For videos however it will also assign metadata to the attachment with the key `instagram_video_url` and the value will be the URL of the video as hosted on instagram's servers. If you'd like to support video playback on the front-end of your site, just check for any `instagram_video_url` values while you're looping through the imported images and use the URL to provide playback in whatever way you prefer.

### Using statuses in your theme:

Using the image status feature, all images will be given a custom status, defaulting to 'draft'. The status of each image can be changed in the media library, or from
within an `Insert Media` popup page. In this way images can be approved before they show on the front of the site, but first a loop needs to be set up that queries
for published images only. This can be acheived by using the `fgram_status` meta key. An example loop might look like the one below:
```php
$args = array(
	'post_parent'		=> $post->ID,					
	'post_type'       	=> 'attachment',
	'post_mime_type'  	=> 'image',
	'posts_per_page' 	=> -1,
	'meta_key' 			=> 'fgram_status',
	'meta_value' 		=> 'published',
	'post_type' 		=> 'attachment'
);
$images = get_posts($args);
```
This will load all imported images into the `$images` variable. You can then loop through and display them as you see fit.
