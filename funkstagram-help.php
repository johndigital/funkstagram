<div style="padding: 20px;">
	Image status is saved for each attachment as the meta value "fgram_status" and has three possible values:<br />
	&emsp;&emsp;&emsp;1. "draft"<br />
	&emsp;&emsp;&emsp;2. "pending_review"<br />
	&emsp;&emsp;&emsp;3. "published"<br /><br />

	When looping through attachments, query for images in the published state like so:<br />
		<strong>
		    $args = array(<br />
			&emsp;&emsp;&emsp;"posts_per_page" => -1,<br />
			&emsp;&emsp;&emsp;"meta_key" => "fgram_status",<br />
			&emsp;&emsp;&emsp;"meta_value" => "published",<br />
			&emsp;&emsp;&emsp;"post_type" => "attachment",<br />
			&emsp;&emsp;&emsp;"post_parent" => $post->ID<br />
			);<br />
			$images = get_posts($args);<br />
		</strong>
</div>