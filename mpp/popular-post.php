<?php
/*Plugin Name: Popular Post*/

/* Popular Post Settings Page */
class popularpost_Settings_Page {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
		add_action( 'admin_footer', array( $this, 'media_fields' ) );
		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
	}
	public function wph_create_settings() {
		$page_title = 'Popular Post';
		$menu_title = 'Popular Post';
		$capability = 'manage_options';
		$slug = 'popularpost';
		$callback = array($this, 'wph_settings_content');
		$icon = 'dashicons-admin-settings';
		$position = 2;
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
	}
	public function wph_settings_content() { ?>
		<div class="wrap">
			<h1>Popular Post</h1>
			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
					settings_fields( 'popularpost' );
					do_settings_sections( 'popularpost' );
					submit_button();
				?>
			</form>
		</div> <?php
	}
	public function wph_setup_sections() {
		add_settings_section( 'popularpost_section', 'This is a popular post fetching', array(), 'popularpost' );
	}
	public function wph_setup_fields() {
		$fields = array(
			array(
				'label' => 'Name',
				'id' => 'name',
				'type' => 'text',
				'section' => 'popularpost_section',
				'desc' => 'Add your name',
				'placeholder' => 'Name',
			),
			array(
				'label' => 'Date',
				'id' => 'date',
				'type' => 'date',
				'section' => 'popularpost_section',
				'desc' => 'Add a date',
				'placeholder' => 'Date',
			),
			array(
				'label' => 'Description',
				'id' => 'desc',
				'type' => 'wysiwyg',
				'section' => 'popularpost_section',
				'desc' => 'Add a description',
				'placeholder' => 'Description',
			),
			array(
				'label' => 'Image',
				'id' => 'image',
				'type' => 'media',
				'section' => 'popularpost_section',
				'desc' => 'Add an Image',
				'placeholder' => 'Image',
			),
			array(
				'label' => 'Email',
				'id' => 'email',
				'type' => 'email',
				'section' => 'popularpost_section',
				'desc' => 'Add an email',
				'placeholder' => 'Email',
			),
			array(
				'label' => 'Smaill Description',
				'id' => 'Desc',
				'type' => 'textarea',
				'section' => 'popularpost_section',
				'desc' => 'Description',
				'placeholder' => 'Some Content',
			),
			array(
				'label' => 'Gender',
				'id' => 'gender',
				'type' => 'radio',
				'section' => 'popularpost_section',
				'options' => array(
					'Male' => 'Male',
					'Female' => 'Female',
					'Others' => 'Others',
				),
				'desc' => 'Add your gender',
				'placeholder' => 'Gender',
			),
			array(
				'label' => 'Religion',
				'id' => 'religion',
				'type' => 'select',
				'section' => 'popularpost_section',
				'options' => array(
					'Hindu' => 'Hindu',
					'Muslim' => 'Muslim',
					'Shikh' => 'Shikh',
					'' => '',
				),
				'desc' => 'Add your religion',
				'placeholder' => 'Relegion',
			),
			array(
				'label' => 'Color',
				'id' => 'color',
				'type' => 'color',
				'section' => 'popularpost_section',
				'desc' => 'Pick a color',
				'placeholder' => 'Color',
			),
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'wph_field_callback' ), 'popularpost', $field['section'], $field );
			register_setting( 'popularpost', $field['id'] );
		}
	}
	public function wph_field_callback( $field ) {
		$value = get_option( $field['id'] );
		switch ( $field['type'] ) {
				case 'media':
					printf(
						'<input style="width: 40%%" id="%s" name="%s" type="text" value="%s"> <input style="width: 19%%" class="button popularpost-media" id="%s_button" name="%s_button" type="button" value="Upload" />',
						$field['id'],
						$field['id'],
						$value,
						$field['id'],
						$field['id']
					);
					printf('<img src="%s" style="width: 350px; height:350px">',$value);
					break;
				case 'radio':
				case 'checkbox':
					if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
						$options_markup = '';
						$iterator = 0;
						foreach( $field['options'] as $key => $label ) {
							$iterator++;
							$options_markup.= sprintf('<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>',
							$field['id'],
							$field['type'],
							$key,
							checked($value[array_search($key, $value, true)], $key, false),
							$label,
							$iterator
							);
							}
							printf( '<fieldset>%s</fieldset>',
							$options_markup
							);
					}
					break;
				case 'select':
				case 'multiselect':
					if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
						$attr = '';
						$options = '';
						foreach( $field['options'] as $key => $label ) {
							$options.= sprintf('<option value="%s" %s>%s</option>',
								$key,
								selected($value[array_search($key, $value, true)], $key, false),
								$label
							);
						}
						if( $field['type'] === 'multiselect' ){
							$attr = ' multiple="multiple" ';
						}
						printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>',
							$field['id'],
							$attr,
							$options
						);
					}
					break;
				case 'textarea':
				printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>',
					$field['id'],
					$field['placeholder'],
					$value
					);
					break;
				case 'wysiwyg':
					wp_editor($value, $field['id']);
					break;
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$field['placeholder'],
					$value
				);
		}
		if( $desc = $field['desc'] ) {
			printf( '<p class="description">%s </p>', $desc );
		}
	}	public function media_fields() {
		?><script>
			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$('.popularpost-media').click(function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id').replace('_button', '');
						_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								$('input#'+id).val(attachment.url);
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
				}
			});
		</script><?php
	}

}
new popularpost_Settings_Page();