<?php 

// Add button above editor if not editing content_block
function add_content_block_icon() {
	echo '<style>
	#add-content-block .dashicons-screenoptions {
		color: #888;
		height: 18px;
		margin: 0 4px 0 0;
		vertical-align: text-top;
		width: 18px;
	}
	#add-content-block {
		padding-left: 0.4em;
	}
	</style>
	<a id="add-content-block" class="button thickbox" title="' . __("Add Adoption Data", 'adoption-widget' ) . '" href="' . plugins_url() . 'pop-adoption.php?type=add_content_block_popup&amp;TB_inline=true&amp;inlineId=content-block-form">
		<div class="dashicons dashicons-screenoptions"></div>' . __("Add Adoption Data", "adoption-widget") . '
	</a>';
}

// Displays the lightbox popup to insert a Adoption Data shortcode to a post/page
function add_content_block_popup() { ?>
	<script>
		function selectContentBlockId(select) {
			content_id = select.options[select.selectedIndex].value;
			content_slug = select.options[select.selectedIndex].getAttribute("data-slug");
		}
		function insertContentBlockShortcode() {
			if (typeof content_id === 'undefined') {
				alert( "<?php _e( 'Please select a Adoption Data', 'adoption-widget' ); ?>" );
				return false;
			}
			var win = window.dialogArguments || opener || parent || top;
			win.send_to_editor( "[content_block id=" + content_id + " slug=" + content_slug + "]" );
		}
	</script>
	<div id="content-block-form" style="display: none;">
		<h3>
			<?php _e( 'Insert Adoption Data', 'adoption-widget' ); ?>
		</h3>
		<p>
			<?php _e( 'Select a Adoption Data below to add it to your post or page.', 'adoption-widget' ); ?>
		</p>
		<p>
			<select class="add-content-block-id" id="add-content-block-id" onchange="selectContentBlockId(this)">
				<option value="">
					<?php _e( 'Select a Adoption Data', 'adoption-widget' ); ?>
				</option>
				<?php 
					$args = array( 'post_type' => 'content_block', 'suppress_filters' => 0, 'numberposts' => -1, 'order' => 'ASC' );
					$content_block = get_posts( $args );
					if ($content_block) {
						foreach( $content_block as $content_block ) : setup_postdata( $content_block );
							echo '<option value="' . $content_block->ID . '" data-slug="' . $content_block->post_name . '">' . $content_block->post_title . '</option>';
						endforeach;
					} else {
						echo '<option value="">' . __( 'No Adoption Data available', 'adoption-widget' ) . '</option>';
					};
				?>
			</select>
		</p>
		<p>
			<input type="button" class="button-primary" value="<?php _e( 'Insert Adoption Data', 'adoption-widget' ) ?>" onclick="insertContentBlockShortcode();"/>
		</p>
	</div>
	
<?php }