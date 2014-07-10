Funkstagram
===========

```
A plugin by funkhaus
    ___ _   _ _  _ _  __
   | __| | | | \| | |/ /
   | _|| |_| | .` | ' <
   |_| _\___/|_|\_|_|\_\
   | || | /_\| | | / __|
   | __ |/ _ \ |_| \__ \
   |_||_/_/ \_\___/|___/

```

### Summary:

Funkstagram is a simple Wordpress plugin used to import photo feeds from Instagram.

### Features:

* Import images for multiple users
* Filter user's images by tag
* Custom post status (Draft|Pending-Review|Published) for each image
* Import all images for a specific tag
* Attach all imported images to a specific page
* Runs automatically every 10 minutes, no Cron needed

### Instructions for use:

1. Download Funkstagram Master and unzip
2. Move entire folder into plugins folder in Wordpress install, and activate through the admin interface
3. Navigate to settings page at `Tools > Funkstagram`
4. Put in Instagram API key. If you don't have one, register a new client at: http://instagram.com/developer/register/
5. Select a page from the drop-down to attach all imported images from. If no page selected, photos will go straight to media library with no parent
6. If you plan to use the image status feature, set a default status for the images to import as.</br>
**Note:** This won't change anything unless your theme is setup to use this feature. The status feature is particularly useful when you don't have full control over what images are being imported (i.e. importing all images tagged `#awesome`.) Status gives you an extra layer so images can be approved in the back end before showing up on the front.
6. If you plan to use the image status feature, set a default status for the images to import as. </br>
**Note:** This won't change anything unless your theme is setup to use this feature. The status feature is particularly useful when you don't have full control over what images are being imported (i.e. importing all images tagged `#awesome`.) Status gives you an extra layer so images can be approved in the back end before showing up on the front.
7. If importing by user, add usernames **or** user IDs into `Users to import` fields.
8. Enter any tags into `Filter by tags` field. If importing by user, only images with these tags from those particular users will be imported. If no users are specified, all images posted to Instagram with those particular tags will be imported. </br>
**Note:** If importing by tag only and the tag is not specific enough, you will end up with a lot of images being imported
9. Click save changes and then click the `Import Now` button to test importing. This may take a while if importing a large number of photos.
10. When Import Now finishes, a log will appear at the bottom of the page. If 0 errors were returned, and images were successfully imported then check the `enable auto-import` box at the top of the screen and save changes. Images will now be imported automatically. </br>
**Developer Note:** If you would rather use a UNIX Cron to run the import rather than a Wordpress cron, use this URL format: `http://example.com/wp-admin/admin-ajax.php?action=funkstagram_import`

### Using statuses in your theme:

Using the image status feature, all images will be given a custom status, defaulting to 'draft'. The status of each image can be changed in the media library, or from
within an `Insert Media` popup page. In this way images can be approved before they show on the front of the site, but first a loop needs to be set up that queries
for published posts only. This can be acheived by using the `fgram_status` meta key. An example loop might look like the one below:
```
$args = array(
    "posts_per_page" => -1,
    "meta_key" => "fgram_status",
    "meta_value" => "published",
    "post_type" => "attachment",
    "post_parent" => $post->ID
);
$images = get_posts($args);
```
You could then loop through `$images` and display them as you see fit.
