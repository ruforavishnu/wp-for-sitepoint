<?php
   /*
   Plugin Name: Bulk Category Creator
   Plugin URI: http://www.ruforaweb.com
   Description: A plugin to bulk-create categories in one go
   Version: 1.0
   Author: Vishnu Ajit
   Author URI: http://twitter.com/vishnuajith310
   License: GPL2
   */


add_action('admin_menu', 'rfr_CategoryCreatorMenu');

function rfr_CategoryCreatorMenu()
{

	add_menu_page('Bulk Category Creator Plugin','Bulk Category Creator','administrator', __FILE__, 'rfr_CategorySettingsPage' , 'dashicons-admin-plugins');

	add_action('admin_init', 'rfr_RegisterPluginSettings');

}


function rfr_RegisterPluginSettings() {
	//register our settings
	
	register_setting( 'rfr-bulk-category-creator-group', 'options_textarea' );

	rfr_CreateCategories();

}

function rfr_CreateCategories()
{
		

		$returnedStr = esc_attr($_POST['options_textarea'] );

		
		$trimmed = trim($returnedStr);
		
		$categories_array = explode(',',$trimmed);

		
	

	foreach ($categories_array as $key => $value)
	{

		
		$catString = $value.'';

		$term = term_exists($value,'category');
		if($term==0 || $term==null)
		{
			create_category($value);
			
		}
		
	}

}

function create_category($value)
{
	$trimmedValue = trim($value);
	$hyphenatedValue = str_replace(" ", "-", $trimmedValue);

	wp_insert_term(
		$trimmedValue,
		'category',
		array(
			'description' => $trimmedValue,
			'slug'=> $hyphenatedValue
			)
		);

	


}



function rfr_CategorySettingsPage() {
?>
<div class="wrap">
<h1>Bulk Category Creater</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'rfr-bulk-category-creator-group' ); ?>
    <?php do_settings_sections( 'rfr-bulk-category-creator-group' ); ?>
    <table class="form-table">
        
        <tr valign="top">
        <th scope="row">Enter categories separated by commas</th>
        <td>
        <textarea cols="50" rows="8" name="options_textarea"></textarea>
        </td>
        </tr>
    </table>
    
    <?php submit_button('Bulk Create Categories'); ?>

</form>
</div>
<?php } ?>




?>