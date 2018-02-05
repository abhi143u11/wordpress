<?php
/**
 * Author: Ole Fredrik Lie
 * URL: http://olefredrik.com
 *
 * FoundationPress functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @package WordPress
 * @subpackage FoundationPress
 * @since FoundationPress 1.0
 */

// Projects Custom Post Type
require_once('library/projects.php');

// Spec Writer Custom Post Type
require_once('library/specifications.php');

// Quote Builder Custom Post Type
require_once('library/quotes.php');

//WP Advanced Search
require_once('library/wp-advanced-search/wpas.php');

/** Various clean up functions */
require_once( 'library/cleanup.php' );

/** Required for Foundation to work properly */
require_once( 'library/foundation.php' );

/** Register all navigation menus */
require_once( 'library/navigation.php' );

/** Add desktop menu walker */
require_once( 'library/menu-walker.php' );

/** Add off-canvas menu walker */
require_once( 'library/offcanvas-walker.php' );

/** Create widget areas in sidebar and footer */
require_once( 'library/widget-areas.php' );

/** Return entry meta information for posts */
require_once( 'library/entry-meta.php' );

/** Enqueue scripts */
require_once( 'library/enqueue-scripts.php' );

/** Add theme support */
require_once( 'library/theme-support.php' );

/** Add Header image */
require_once( 'library/custom-header.php' );

// Only admins can access WP Dashboard
add_action( 'init', 'solar_blockadmin' );
function solar_blockadmin() 
{
	if (is_admin() && !current_user_can('administrator') && ! (defined('DOING_AJAX') && DOING_AJAX)) :
		wp_redirect(home_url());
		die();
	endif;
}

// Add gravity forms to footer so it is instantiated AFTER jquery is loaded
add_filter('gform_init_scripts_footer', 'init_scripts');
function init_scripts() {
    return true;
}

// Quote Forms Preview Image
$_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $_SERVER['REQUEST_URI_PATH']);
if (count($segments) >= 4 && $segments[1] == 'quote') :
	$args = array('post_type' => 'quote', 'name' => $segments[2]);
	$post = new WP_Query($args);
	$quote_post_title = (!empty($post->post)) ? $post->post->post_title : '';
	if (!empty($quote_post_title)) :
		wp_enqueue_script('si_quote_script', get_template_directory_uri() . '/js/quotes/init.js');
	endif;
endif;

// Add user fields to new/update forms
function custom_user_profile_fields($user){
	$states = array('Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','West Virginia','Wisconsin','Wyoming','District of Columbia','Puerto Rico','Guam','American Samoa','U.S. Virgin Islands','Northern Mariana Islands');
	
	$countries = array('United States of America', 'Canada', 'Mexico', 'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua & Deps', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina', 'Burma', 'Burundi', 'Cambodia', 'Cameroon', 'Cape Verde', 'Central African Rep', 'Chad', 'Chile', 'China', 'Republic of China', 'Colombia', 'Comoros', 'Democratic Republic of the Congo', 'Republic of the Congo', 'Costa Rica,', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Danzig', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gaza Strip', 'The Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Holy Roman Empire', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Republic of Ireland', 'Israel', 'Italy', 'Ivory Coast', 'Jamaica', 'Japan', 'Jonathanland', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'North Korea', 'South Korea', 'Kosovo', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mount Athos', 'Mozambique', 'Namibia', 'Nauru', 'Nepal', 'Newfoundland', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Norway', 'Oman', 'Ottoman Empire', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Prussia', 'Qatar', 'Romania', 'Rome', 'Russian Federation', 'Rwanda', 'St Kitts & Nevis', 'St Lucia', 'Saint Vincent & the', 'Grenadines', 'Samoa', 'San Marino', 'Sao Tome & Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad & Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe');
	
    if (is_object($user)) :
        $phone = esc_attr( get_the_author_meta( 'phone', $user->ID ) );
		$fax = esc_attr( get_the_author_meta( 'fax', $user->ID ) );
		$salesrep = esc_attr( get_the_author_meta( 'sales_rep', $user->ID ) );
		$company = esc_attr( get_the_author_meta( 'company', $user->ID ) );
		$address = esc_attr( get_the_author_meta( 'address', $user->ID ) );
		$address2 = esc_attr( get_the_author_meta( 'address2', $user->ID ) );
		$city = esc_attr( get_the_author_meta( 'city', $user->ID ) );
		$state = esc_attr( get_the_author_meta( 'state', $user->ID ) );
		$zip = esc_attr( get_the_author_meta( 'zip', $user->ID ) );
		$country = esc_attr( get_the_author_meta( 'country', $user->ID ) );
		$referrer = esc_attr( get_the_author_meta( 'referrer', $user->ID ) );
    else :
        $phone = null;
        $fax = null;
        $salesrep = null;
        $company = null;
        $address = null;
        $address2 = null;
        $city = null;
        $state = null;
        $zip = null;
        $country = null;
        $referrer = null;
	endif;
	
	global $current_user; 
	if ($current_user->roles[0] == 'administrator' && $user->roles[0] == 'osr') : 
?>
    	<p class="form-sales_rep">
	    	<label for="sales_rep"><?php _e('Sales Rep', 'profile'); ?></label>
	    	<select name="sales_rep" id="sales_rep">
		    	<option value="">Choose a Sales Rep</option>
				<?php
				$reps = new WP_Query(array('post_type' => 'salesrep'));
				foreach ($reps->posts as $row) :
					//echo '<pre>'; print_r($row); echo '</pre>';
				?>
					<option value="<?=$row->ID?>"<?=($row->ID == $salesrep) ? ' selected' : ''; ?>><?=$row->post_title?></option>
				<?php endforeach; ?>
	    	</select>
    	</p>		
	<?php endif; ?>
	
    <p class="form-company">
		<label for="company"><?php _e('Company', 'profile'); ?></label>
		<input class="text-input" name="company" type="text" id="company" value="<?=$company?>" />
	</p>
	<p class="form-phone">
		<label for="phone"><?php _e('Phone', 'profile'); ?></label>
		<input class="text-input" name="phone" type="text" id="phone" value="<?=$phone?>" />
	</p>
	<p class="form-fax">
		<label for="fax"><?php _e('Fax', 'profile'); ?></label>
		<input class="text-input" name="fax" type="text" id="fax" value="<?=$fax?>" />
	</p>
	<p class="form-address">
		<label for="address"><?php _e('Address', 'profile'); ?></label>
		<input class="text-input" name="address" type="text" id="address" value="<?=$address?>" />
	</p>
	<p class="form-address2">
		<label for="address2"><?php _e('Address 2', 'profile'); ?></label>
		<input class="text-input" name="address2" type="text" id="address2" value="<?=$address2?>" />
	</p>
	<p class="form-city">
		<label for="city"><?php _e('City', 'profile'); ?></label>
		<input class="text-input" name="city" type="text" id="city" value="<?=$city?>" />
	</p>
	<p class="form-state">
		<label for="state"><?php _e('State', 'profile'); ?></label>
		<select name="state">
			<?php foreach ($states as $row) : ?>
				<option value="<?=$row?>"<?=($state == $row) ? ' selected' : ''?>><?=$row?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p class="form-zip">
		<label for="zip"><?php _e('Zip', 'profile'); ?></label>
		<input class="text-input" name="zip" type="text" id="zip" value="<?=$zip?>" />
	</p>
	<p class="form-country">
		<label for="country"><?php _e('Country', 'profile'); ?></label>
		<select name="country">
			<?php foreach ($countries as $row) : ?>
				<option value="<?=$row?>"<?=($country == $row) ? ' selected' : ''?>><?=$row?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p class="form-referrer">
		<label for="referrer"><?php _e('Referrer', 'profile'); ?></label>
		<input class="text-input" name="referrer" type="text" id="referrer" value="<?=$referrer?>" />
	</p>
	
<?php
}
add_action( 'show_user_profile', 'custom_user_profile_fields' );
add_action( 'edit_user_profile', 'custom_user_profile_fields' );
add_action( 'user_new_form', 'custom_user_profile_fields' );

// Save custom user fields
function save_custom_user_profile_fields($user_id){
    # again do this only if you can
    if(!current_user_can('manage_options'))
        return false;
	
    # save my custom field
    update_user_meta($user_id, 'company', $_POST['company']);
    update_user_meta($user_id, 'phone', $_POST['phone']);
    update_user_meta($user_id, 'fax', $_POST['fax']);
    update_user_meta($user_id, 'address', $_POST['address']);
    update_user_meta($user_id, 'address2', $_POST['address2']);
    update_user_meta($user_id, 'city', $_POST['city']);
    update_user_meta($user_id, 'state', $_POST['state']);
    update_user_meta($user_id, 'zip', $_POST['zip']);
    update_user_meta($user_id, 'country', $_POST['country']);
    update_user_meta($user_id, 'referrer', $_POST['referrer']);
    if (!empty($_POST['sales_rep'])) 
    	update_user_meta($user_id, 'sales_rep', $_POST['sales_rep']);
}
add_action('user_register', 'save_custom_user_profile_fields');
add_action('profile_update', 'save_custom_user_profile_fields');

// Establish frontend user roles
add_role('osr', __('OSR'),array(
	'read' => true, // true allows this capability
	'manage_options' => true, // Allows user to edit their own profile
	'edit_posts' => false, // Allows user to edit their own posts
	'edit_pages' => false, // Allows user to edit pages
	'edit_others_posts' => false, // Allows user to edit others posts not just their own
	'create_posts' => false, // Allows user to create new posts
	'manage_categories' => false, // Allows user to manage post categories
	'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
	'edit_themes' => false, // false denies this capability. User can’t edit your theme
	'install_plugins' => false, // User cant add new plugins
	'update_plugin' => false, // User can’t update any plugins
	'update_core' => false // user cant perform core updates
));

add_role('dealer', __('Dealer'),array(
	'read' => true, // true allows this capability
	'manage_options' => true, // Allows user to edit their own profile
	'edit_posts' => false, // Allows user to edit their own posts
	'edit_pages' => false, // Allows user to edit pages
	'edit_others_posts' => false, // Allows user to edit others posts not just their own
	'create_posts' => false, // Allows user to create new posts
	'manage_categories' => false, // Allows user to manage post categories
	'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
	'edit_themes' => false, // false denies this capability. User can’t edit your theme
	'install_plugins' => false, // User cant add new plugins
	'update_plugin' => false, // User can’t update any plugins
	'update_core' => false // user cant perform core updates
));

add_role('user', __('User'),array(
	'read' => true, // true allows this capability
	'manage_options' => true, // Allows user to edit their own profile
	'edit_posts' => false, // Allows user to edit their own posts
	'edit_pages' => false, // Allows user to edit pages
	'edit_others_posts' => false, // Allows user to edit others posts not just their own
	'create_posts' => false, // Allows user to create new posts
	'manage_categories' => false, // Allows user to manage post categories
	'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
	'edit_themes' => false, // false denies this capability. User can’t edit your theme
	'install_plugins' => false, // User cant add new plugins
	'update_plugin' => false, // User can’t update any plugins
	'update_core' => false // user cant perform core updates
));

// Process Spec Writer Submissions
function gfpdfe_post_pdf_save($form_id, $lead_id, $arguments, $filename)
{	
    $form = GFFormsModel::get_form_meta($form_id);
	if ($form['fields'][0]['defaultValue'] == 'specification')
	{
		$lead = RGFormsModel::get_lead($lead_id); 
		
		require_once 'library/PHPWord/src/PhpWord/Autoloader.php';
		\PhpOffice\PhpWord\Autoloader::register();
		
		// Creating the new document...
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		
		/* Note: any element you append to a document must reside inside of a Section. */
		
		// Adding an empty Section to the document...
		$section = $phpWord->addSection();
		// Adding Text element to the Section having font styled by default...
		foreach ($form['fields'] as $field)
		{
			if ($field['type'] == 'checkbox')
			{
				foreach ($field['inputs'] as $input)
				{
					if (!empty($lead[$input['id']]))
					{
						$section->addText(htmlspecialchars($field['label']));
						$section->addText(htmlspecialchars($lead[$input['id']]));
					}
				}
			} else 
			{
				$section->addText(htmlspecialchars($field['label']));
				$section->addText(htmlspecialchars($lead[$field['id']]));
			}
			
		};
		$section->addText(print_r($lead,true));
		$section->addText(print_r($form['fields'],true));
			
		// Adding Text element with font customized using explicitly created font style object...
		$fontStyle = new \PhpOffice\PhpWord\Style\Font();
		$fontStyle->setBold(true);
		$fontStyle->setName('Tahoma');
		$fontStyle->setSize(13);
		$myTextElement = $section->addText(
		    htmlspecialchars('"Believe you can and you\'re halfway there." (Theodor Roosevelt)')
		);
		$myTextElement->setFontStyle($fontStyle);
				
		// Get new filename
		$filename = rtrim(basename($filename),'.pdf');

		// Saving the document as OOXML file...
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('wp-content/uploads/PDF_EXTENDED_TEMPLATES/output/' . $form_id . $lead_id . '/' . $filename . '.docx');
	}
}
add_action('gfpdf_post_pdf_save', 'gfpdfe_post_pdf_save', 10, 4);

// Generate Project PDF
function generate_project_pdf( $post_id = null, $post = null, $update = null) {
	if (empty($post) && !empty($post_id))
		$post = get_post($post_id);
    if ( $post->post_type != 'project' || $post->post_name == '')
        return;

	// Build HTML
	// http://mpdf1.com/manual/
	$bodyHTML = file_get_contents(get_template_directory() . '/assets/project_pdf/product_template.html');
	
	$bodyHTML = str_replace('{LOGO}', '<img src="' . get_template_directory() . '/assets/project_pdf/logo.jpg" width="200" />', $bodyHTML);
	$bodyHTML = str_replace('{COMPANY}', $post->post_title, $bodyHTML);
	$bodyHTML = str_replace('{ADDRESS1}', $post->post_title, $bodyHTML);
	$bodyHTML = str_replace('{ADDRESS2}', $post->post_title, $bodyHTML);
	$bodyHTML = str_replace('{PHONE}', $post->post_title, $bodyHTML);
	$bodyHTML = str_replace('{WEBSITE}', $post->post_title, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_TITLE}', $post->post_title, $bodyHTML);
	
	$string = '';
	if (have_rows('images',$post->ID)) :
		$i = 0;
		$string .= '<table cellspacing="20" cellpadding="0" border="0" width="100%"><tr>';
		while (have_rows('images',$post->ID)) :
			the_row();
			$image = get_sub_field('image');
			//$image = str_replace(home_url(),'..',$image);
			if (@getimagesize($image))
				$string .= '<td><img src="' . $image . '" width="100%" /></td>';
		endwhile;
		$string .= '</tr></table>';
    endif;
    $bodyHTML = str_replace('{PROJECT_IMAGES}', $string, $bodyHTML); //images
	
	$bodyHTML = str_replace('{PROJECT_DESCRIPTION}', $post->post_content, $bodyHTML);
	
	$string = '';
	if ($data = get_field('location')) :
		$x = 0;
		$string = '';
		if (is_array($data)) :
			foreach ($data as $a) :
				$string .= $a . ', ';
				$x++;
			endforeach;
			$string = rtrim($string, ', ');
		else :
			$string = $data;
		endif;
		$p1 = ($x > 1) ? 's' : '';
	endif;
	$bodyHTML = str_replace('{P1}', $p1, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_LOCATION}', $string, $bodyHTML);
	
	$string = '';
	if ($data = get_field('application')) :
		$x = 0;
		$string = '';
		if (is_array($data)) :
			foreach ($data as $a) :
				$string .= $a . ', ';
				$x++;
			endforeach;
			$string = rtrim($string, ', ');
		else :
			$string = $data;
		endif;
		$p2 = ($x > 1) ? 's' : '';
	endif;
	$bodyHTML = str_replace('{P2}', $p2, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_APPLICATION}', $string, $bodyHTML);
	
	$string = '';
	if ($data = get_field('exterior_color')) :
		$x = 0;
		$string = '';
		if (is_array($data)) :
			foreach ($data as $a) :
				$string .= $a . ', ';
				$x++;
			endforeach;
			$string = rtrim($string, ', ');
		else :
			$string = $data;
		endif;
		$p3 = ($x > 1) ? 's' : '';
	endif;
	$bodyHTML = str_replace('{P3}', $p3, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_EXTERIOR_COLORS}', $string, $bodyHTML);
	
	$string = '';
	if ($data = get_field('interior_color')) :
		$x = 0;
		$string = '';
		if (is_array($data)) :
			foreach ($data as $a) :
				$string .= $a . ', ';
				$x++;
			endforeach;
			$string = rtrim($string, ', ');
		else :
			$string = $data;
		endif;
		$p4 = ($x > 1) ? 's' : '';
	endif;
	$bodyHTML = str_replace('{P4}', $p4, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_INTERIOR_COLORS}', $string, $bodyHTML);
	
	$string = '';
	if ($data = get_field('glaze')) :
		$x = 0;
		$string = '';
		if (is_array($data)) :
			foreach ($data as $a) :
				$string .= $a . ', ';
				$x++;
			endforeach;
			$string = rtrim($string, ', ');
		else :
			$string = $data;
		endif;
		$p5 = ($x > 1) ? 's' : '';
	endif;
	$bodyHTML = str_replace('{P5}', $p5, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_GLAZE}', $string, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_WIDTH}', get_field('width',$post->ID), $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_LENGTH_PROJECTION}', get_field('length_/_projection',$post->ID), $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_RIDGE_HEIGHT}', get_field('ridge_height',$post->ID), $bodyHTML);
	
	if ($prod = get_field('products')) :
		$ia = 0;
		$ib = 0;
		$ic = 0;
		$string = '';
		$stringb = '';
		$stringc = '';
		$stringd = '';
		//project has more than one product
		if (is_array($prod)) :
			foreach ($prod as $a) :
				$string .= $a . ', ';
				$ia++;
				if ($type = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$a))) . '_product_types')) :
					if (is_array($type)) :
						foreach ($type as $b) :
							$stringb .= $b . ', ';
							$ib++;
						endforeach;
					else : 
						$stringb = $type;
					endif;
				endif;
				
				if ($detail = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$a))) . '_product_details')) :
					if (is_array($detail)) :
						foreach ($detail as $c) :
							$stringc .= $c . ', ';
							$ic++;
						endforeach;
					else :
						$stringc = $detail;
					endif;
				endif;
			endforeach;
			$string = rtrim($string, ', ');
			$stringb = rtrim($stringb, ', ');
			$stringc = rtrim($stringc, ', ');
		else : //if project has only one product defined (default)
			$string = $prod;
			if ($type = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$prod))) . '_product_types')) :
				if (is_array($type)) :
					foreach ($type as $b) :
						$stringb .= $b . ', ';
						$ib++;
					endforeach;
				else :
					$stringb = $type;
				endif;
			else:
				$stringb = 'None';
			endif;
			
			if ($detail = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$prod))) . '_product_details')) :
				if (is_array($detail)) :
					foreach ($detail as $c) :
						$stringc .= $c . ', ';
					endforeach;
				else :
					$stringc = $detail;
				endif;
			else:
				$stringc = 'None';
			endif;
			
			if ($glass = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$prod))) . '_product_glass')) :
				if (is_array($glass)) :
					foreach ($glass as $d) :
						$stringd .= $d . ', ';
					endforeach;
				else :
					$stringd = $glass;
				endif;
			else:
				$stringd = 'None';
			endif;
			$string = rtrim($string, ', ');
			$stringb = rtrim($stringb, ', ');
			$stringc = rtrim($stringc, ', ');
			$stringd = rtrim($stringd, ', ');
		endif;
		
		$p6 = ($ia > 1) ? 's' : '';
		$p7 = ($ib > 1) ? 's' : '';
	endif;
	$bodyHTML = str_replace('{P6}', $p6, $bodyHTML);
	$bodyHTML = str_replace('{P7}', $p7, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_PRODUCT}', $string, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_PRODUCT_TYPE}', $stringb, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_PRODUCT_DETAILS}', $stringc, $bodyHTML);
	$bodyHTML = str_replace('{PROJECT_PRODUCT_GLASS}', $stringd, $bodyHTML);
	$disclaimer = 'All text, graphics, images, animation, videos, and other works on this website are the copyrighted works of Solar Innovations, Inc. All Rights Reserved. Any unauthorized redistribution or reproduction of any copyrighted materials on this website is strictly prohibited. The trademarks, logos, and service marks (collectively the "Trademarks") displayed on the website are Trademarks of Solar Innovations, Inc. and/or its affiliates. Some Trademarks appear on the website with permission from their respective owners. Nothing contained on the website should be construed as granting any license or right to use any Trademark displayed on the website. Unauthorized use of the Trademarks is strictly prohibited and may constitute trademark infringement. All drawings included in this website are the exclusive sole property of Solar Innovations, Inc., and are considered both confidential and proprietary. Any use of the information, design or drawings contained herein for any purpose whatsoever without the expressed written consent of a Solar Innovations, Inc. authorized individual is forbidden.';
	$bodyHTML = str_replace('{DISCLAIMER}', $disclaimer, $bodyHTML);
	
	$upload_dir = wp_upload_dir();
	//require_once('wp-content/plugins/gravity-forms-pdf-extended/mPDF/mpdf.php');
	require_once('../wp-content/plugins/gravity-forms-pdf-extended/mPDF/mpdf.php');
	
	$mpdf=new mPDF();
	$mpdf->showImageErrors = true;
	$mpdf->WriteHTML($bodyHTML);
	
	//Save Project PDF
	//$file = $mpdf->Output('wp-content/uploads/project_pdfs/' . $post->post_name . '.pdf','F');
	$file = $mpdf->Output('../wp-content/uploads/project_pdfs/' . $post->post_name . '.pdf','F');
}
add_action( 'save_post', 'generate_project_pdf', 10, 3 );

// Pre-populate grtavity form
add_filter( 'gform_pre_render', 'gf_populate_form' );
function gf_populate_form($form) {
	if (isset($_REQUEST['edit-form']))
		$entry = RGFormsModel::get_lead($_REQUEST['edit-form']);

	echo '<style type="text/css">';
	foreach( $form['fields'] as &$field )
	{
		if (in_array($field->type,array('text','phone','email','date')))
		{
			if (!empty($entry))
			{
				$field->defaultValue = $entry[$field['id']];
			}	
		} else if ($field->type == 'address') 
		{
			foreach ($field->inputs as &$input)
				$input['defaultValue'] = $entry[$input['id']];
		} else if ($field->type == 'checkbox' || $field->type == 'radio')
		{
			$i = 0;
			foreach ($field->choices as &$choice)
			{
				if (isset($entry))
					if (!empty($entry[$field->inputs[$i]['id']]))
						$choice['isSelected'] = 'true';
				
				//next determine if this checkbox should be indented
				if (substr($choice['text'], 0, 1) === '*')
				{
					$count = 0;
					while (substr($choice['text'], 0, 1) === '*')
					{
						$choice['text'] = preg_replace('/\*/', '', $choice['text'], 1);
						//$choice['value'] = preg_replace('/\*/', '', $choice['value'], 1);
						$count++;
					}
					
					$this_input_id = ($field->type == 'checkbox') ? $field->inputs[$i]['id'] : $field->id . '_' . $i;
				
					$left = 30 * $count;
					echo 'input#choice_' . $form['id'] . '_' . str_replace('.','_',$this_input_id) . ' { margin-left:' . $left . 'px } ';
					$left += 24;
					echo 'label#label_' . $form['id'] . '_' . str_replace('.','_',$this_input_id) . ' { margin-left:' . $left . 'px } '; 
				}
				$i++;
			}
		}
	}
	echo '</style>';
	return $form;
}

// Handle PFD/DOC Delete/Downaload Links
if (isset($_REQUEST['delete-form']) && isset($_REQUEST['entry'])) :	
	if ($entry = GFAPI::get_entry($_REQUEST['entry'])) :
		if ($entry['created_by'] == get_current_user_id()) :
			GFAPI::delete_entry($_REQUEST['entry']);
			function recursiveRemoveDirectory($directory)
			{
			    foreach(glob("{$directory}/*") as $file) :
			        if(is_dir($file))
			            recursiveRemoveDirectory($file);
			        else
			            unlink($file);
			    endforeach;
			    rmdir($directory);
			}
			recursiveRemoveDirectory('../wp-content/uploads/PDF_EXTENDED_TEMPLATES/output/' . $entry['form_id'] . $entry['id']);
		endif;
	endif;
elseif (isset($_REQUEST['download-form']) && $_REQUEST['download-form'] && isset($_REQUEST['entry'])) :
	$entry = GFAPI::get_entry($_REQUEST['entry']);
	if ($entry['created_by'] == get_current_user_id()) :
		$form = GFAPI::get_form( $entry['form_id'] );
		$ft = str_replace(':', '-', $form['title']);
		$filename = $ft . '_' . $entry['form_id'] . $entry['id'] . '.' . $_REQUEST['type'];
		//echo 'wp-content/uploads/PDF_EXTENDED_TEMPLATES/output/' . $entry['form_id'] . $entry['id'] . '/' . $filename;die();
		header('Content-Type: application/' . $_REQUEST['type']);
		header('Content-Disposition: attachment; filename=' . $filename);
		readfile('wp-content/uploads/PDF_EXTENDED_TEMPLATES/output/' . $entry['form_id'] . $entry['id'] . '/' . $filename);
		die();
	endif;
endif;

// Handle Project PDF Download Links
if (isset($_REQUEST['download-project']) && isset($_REQUEST['project']) && isset($_REQUEST['type'])) :
	$post = get_post($_REQUEST['project']);
	//generate_project_pdf($post->ID);
	header('Content-Type: application/' . $_REQUEST['type']);
	header('Content-Disposition: attachment; filename=' . $post->post_name . '.' . $_REQUEST['type']);
	readfile('wp-content/uploads/project_pdfs/' . $post->post_name . '.' . $_REQUEST['type']);
	die();
endif;

// File Size Calculator
function FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem) :
        if($bytes >= $arItem["VALUE"]) :
            $result = $bytes / $arItem["VALUE"];
            $result = strval(round($result))." ".$arItem["UNIT"];
            break;
        endif;
    endforeach;
    return $result;
}

// Build Project Gallery Form via WPAS
function project_gallery_filter_form() {
 
    $args = array();
		//$args['debug'] = true;
		$args['wp_query'] = array( 'post_type' => array('project'), 
								   'posts_per_page' => 30,
		                           'orderby' => 'title', 
		                           'order' => 'ASC',
		                         );
		                         
		$args['fields'][] = array(  'type' => 'search',
									'placeholder' => 'Search'
								);
								
		$args['fields'][] = array( 'type' => 'submit',
		                           'class' => '',
		                           'value' => 'Filter' );
								
		$args['fields'][] = array('type' => 'reset',
                              'title' => 'Reset',
                              );
        
        $args['fields'][] = array('type' => 'html',
                          'value' => '<a class="button tiny reset" onclick="history.go(-1);">Go back</a>');
		
		$products = get_field_object('field_5595540c6184c');
		$allproducts = array();
		foreach ($products['choices'] as $product)
		{
			if ($product != 'Filter by Product') //if using checkbox, comment 'else' out
				$allproducts[$product] = $product;
			else 
				$allproducts[] = $product;
		}
		$args['fields'][] = array(  'type' => 'meta_key',
									'label' => 'Products',
									'format' => 'select',
									'meta_key' => 'products',
									'data_type' => 'ARRAY<CHAR>',
									'compare' => 'LIKE',
									'values' => $allproducts
								);

		foreach ($products['choices'] as $product)
		{
			if ($product != 'Select a Product')
			{
				$search = $product . ' Product Types'; 
				$string = strtolower(str_replace(' ', '_', str_replace('/ ', '', $product))) . '_product_types';
				$content = solid_search_posts($string);	
				$types = array();
				if (!empty($content)) :
					$content = unserialize($content);
					foreach ($content['choices'] as $type)
						if ($type != 'Select a Type')
							$types[$type] = $type;
					
					$args['fields'][] = array(  'type' => 'meta_key',
												'label' => $search,
												'format' => 'checkbox',
												'relation' => 'OR',
												'data_type' => 'ARRAY<CHAR>',
												'compare' => 'LIKE',
												'class' => 'product_types_group',
												'meta_key' => strtolower(str_replace(' ', '_', str_replace('/ ', '', $product))) . '_product_types',
												'values' => $types, 
											);
				endif;
				
				$search = $product . ' Product Details'; 
				$string = strtolower(str_replace(' ', '_', str_replace('/ ', '', $product))) . '_product_details';
				$content = solid_search_posts($string);	
				$types = array();
				if (!empty($content)) :
					$content = unserialize($content);
					foreach ($content['choices'] as $type)
						if ($type != 'Select a Detail')
							$types[$type] = $type;
					
					$args['fields'][] = array(  'type' => 'meta_key',
												'label' => $search,
												'format' => 'checkbox',
												'relation' => 'OR',
												'data_type' => 'ARRAY<CHAR>',
												'compare' => 'LIKE',
												'class' => 'product_details_group',
												'meta_key' => strtolower(str_replace(' ', '_', str_replace('/ ', '', $product))) . '_product_details',
												'values' => $types, 
											);
				endif;
				
				$search = $product . ' Product Glass'; 
				$string = strtolower(str_replace(' ', '_', str_replace('/ ', '', $product))) . '_product_glass';
				$content = solid_search_posts($string);	
				$types = array();
				if (!empty($content)) :
					$content = unserialize($content);
					foreach ($content['choices'] as $type)
						if ($type != 'Select an Amount')
							$types[$type] = $type;
										
					$args['fields'][] = array(  'type' => 'meta_key',
												'label' => $search,
												'format' => 'checkbox',
												'relation' => 'OR',
												'data_type' => 'ARRAY<CHAR>',
												'compare' => 'LIKE',
												'class' => 'product_glass_group',
												'meta_key' => strtolower(str_replace(' ', '_', str_replace('/ ', '', $product))) . '_product_glass',
												'values' => $types, 
											);
				endif;
			}
		}

		$states = search_meta_key('field_559550837d1c3');
		$allstates = array();
		foreach ($states['choices'] as $state)
		{
			if ($state != 'Select a Location')
				$allstates[$state] = $state;
		}
		$args['fields'][] = array(  'type' => 'meta_key',
									'label' => 'Location',
									'format' => 'checkbox',
									'relation' => 'OR',
									'compare' => 'IN',
									'meta_key' => 'location',
									'values' => $allstates 
								);
		
		$interiorColors = search_meta_key('field_559731606f257');
		$allcolors = array();
		foreach ($interiorColors['choices'] as $color)
		{
			if ($color != 'Select a Color') //if using checkbox, comment 'else' out
				$allcolors[$color] = $color;
			//else 
				//$allcolors[] = $color;
		}
		$args['fields'][] = array(  'type' => 'meta_key',
									'label' => 'Interior Color',
									'format' => 'checkbox',
									'relation' => 'OR',
									'data_type' => 'ARRAY<CHAR>',
									'compare' => 'LIKE',
									'meta_key' => 'interior_color',
									'values' => $allcolors 
								);
		
		$exteriorColors = search_meta_key('field_559731716f258');
		$allcolors = array();
		foreach ($exteriorColors['choices'] as $color)
		{
			if ($color != 'Select a Color') //if using checkbox, comment 'else' out
				$allcolors[$color] = $color;
			//else 
				//$allcolors[] = $color;
		}
		$args['fields'][] = array(  'type' => 'meta_key',
									'label' => 'Exterior Color',
									'format' => 'checkbox',
									'relation' => 'OR',
									'data_type' => 'ARRAY<CHAR>',
									'compare' => 'LIKE',
									'meta_key' => 'exterior_color',
									'values' => $allcolors 
								);
		
		$applications = search_meta_key('field_559551c77d1c6');
		$allapplications = array();
		foreach ($applications['choices'] as $application)
		{
			if ($application != 'Select an Application') //if using checkbox, comment 'else' out
				$allapplications[$application] = $application;
			else 
				$allapplications[] = $application;
		}
		$args['fields'][] = array(  'type' => 'meta_key',
									'label' => 'Application',
									'format' => 'checkbox',
									'relation' => 'OR',
									'data_type' => 'ARRAY<CHAR>',
									'compare' => 'LIKE',
									'meta_key' => 'application',
									'values' => $allapplications 
								);
								
		$glazes = search_meta_key('field_5597319f6f259');
		$allglazes = array();
		foreach ($glazes['choices'] as $glaze)
		{
			if ($glaze != 'Select a Glaze') //if using checkbox, comment 'else' out
				$allglazes[$glaze] = $glaze;
			else 
				$allglazes[] = $glaze;
		}
		$args['fields'][] = array(  'type' => 'meta_key',
									'label' => 'Glaze',
									'format' => 'checkbox',
									'relation' => 'OR',
									'data_type' => 'ARRAY<CHAR>',
									'compare' => 'LIKE',
									'meta_key' => 'glaze',
									'values' => $allglazes 
								);
								
		/*
		$args['fields'][] = array( 'type' => 'orderby', 
		                           'format' => 'select', 
		                           'label' => 'Order by', 
		                           'values' => array('title' => 'Title', 
		                                             'date' => 'Date Added') );
		*/
		/*
		$args['fields'][] = array( 'type' => 'order', 
		                           'format' => 'radio', 
		                           'label' => 'Order', 
		                           'values' => array('ASC' => 'ASC', 'DESC' => 'DESC'), 
		                           'default' => 'ASC' );
		*/
		/*
		$args['fields'][] = array( 'type' => 'posts_per_page', 
		                           'format' => 'select', 
		                           'label' => 'Results per page', 
		                           'values' => array(2=>2, 5=>5, 10=>10), 
		                           'default' => 10 );
		*/
 
    register_wpas_form('project-gallery-filter-form', $args);    
}
$url = explode('?', 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
$gpid = url_to_postid($url[0]);
if ($gpid == 62) 
	add_action('init', 'project_gallery_filter_form');

function search_meta_key($key = ''){
	global $wpdb;
	$r = $wpdb->get_col($wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '%s'", $key));
	if (isset($r[0]))
		return unserialize($r[0]);
	else
		return unserialize($r);
}

function solid_search_posts($string = ''){
	global $wpdb;
	$r = $wpdb->get_col($wpdb->prepare( "SELECT post_content FROM {$wpdb->posts} WHERE post_excerpt = '%s'", $string));
	return $r[0];
}

function search_meta_value($value = ''){
	global $wpdb;
	//"SELECT meta_value FROM {$wpdb->postmeta} WHERE MATCH(meta_value) AGAINST('%s' IN BOOLEAN MODE)",'%',$value.'%'
	//"SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_value LIKE '%s'", '%'.$value.'%'
	$r = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_value LIKE '%s'", '%"'.$value.'"%'));
	
	if (isset($r[0]))
		return unserialize($r[0]);
	else
		return unserialize($r);
}

function formatPhone($n)
{
	if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $n, $matches)) :
	    $result = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
	    return $result;
	endif;
	return $n;
}

// Custom Menu Creation 
register_nav_menus(array('primary' => __( 'Primary Menu', 'theme_slug' )));

function display_primary_menu() {
	wp_nav_menu( array(
		'theme_location' => 'primary',
		'menu' => 'Primary Menu',
		'container' => false, // remove nav container
		'container_class' => '', // class of container
		'menu_class' => '', // adding custom nav class
		'before' => '', // before each link <a>
		'after' => '', // after each link </a>
		'link_before' => '', // before each link text
		'link_after' => '', // after each link text
		'depth' => 5, // limit the depth of the nav
		'fallback_cb' => false, // fallback function (see below)
		'walker' => new top_bar_walker()
	) );
}

// Support For Foundation Menus
class top_bar_walker extends Walker_Nav_Menu {

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		$element->has_children = !empty( $children_elements[$element->ID] );
		$element->classes[] = ( $element->current || $element->current_item_ancestor ) ? 'active' : '';
		$element->classes[] = ( $element->has_children ) ? 'has-dropdown not-click' : '';

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$item_html = '';
		parent::start_el( $item_html, $object, $depth, $args );


		$classes = empty( $object->classes ) ? array() : (array) $object->classes;
		if ( in_array('label', $classes) ) {
			$item_html = preg_replace( '/<a[^>]*>(.*)<\/a>/iU', '<label>$1</label>', $item_html );
		}



		$output .= $item_html;
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "\n<ul class=\"sub-menu dropdown\">\n";
	}

}

register_nav_menus( array(
	'footer' => __( 'Footer Menu', 'theme_slug' ),
) );

function display_footer_menu() {
	wp_nav_menu( array(
		'theme_location' => 'footer',
		'menu' => 'Footer Menu',
		'container' => false, // remove nav container
		'menu_class' => 'inline-list', // adding custom nav class
		'fallback_cb' => false, // fallback function (see below)
	) );
}

// Register Custom Contact Sidebar
add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Contact Sidebar', 'theme-slug' ),
        'id' => 'sidebar-contact',
        'description' => __( 'This Widget is for the Contact Page', 'theme-slug' ),
        'class' => 'contact-sidebar',
        'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
    ) );
}

// Support For Options Page
if( function_exists('acf_add_options_page') ) {
	
		acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}
// Support For Breadcrumbs
function qt_custom_breadcrumbs() {
 
  $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = '&raquo;'; // delimiter between crumbs
  $home = 'Home'; // text for the 'Home' link
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
 
  global $post;
  $homeLink = get_bloginfo('url');
  
  if (is_home() || is_front_page()) {
 
    if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
 
  } else {
 
    echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
 
    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
      echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
 
    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;
 
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
 
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        echo $cats;
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
 
    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
 
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
 
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
 
    echo '</div>';
 
  }
} // end qt_custom_breadcrumbs()

// Remove Admin bar on frontend
function remove_admin_bar() 
{
	if (!current_user_can('administrator') && !is_admin())
		show_admin_bar(false);
}
add_action('after_setup_theme', 'remove_admin_bar');
?>
<?php

remove_action('wp_head', 'wp_generator');


add_filter( 'template_include', 'var_template_include', 1000 );
function var_template_include( $t ){
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
}

function get_current_template( $echo = false ) {
    if( !isset( $GLOBALS['current_theme_template'] ) )
        return false;
    if( $echo )
        echo $GLOBALS['current_theme_template'];
    else
        return $GLOBALS['current_theme_template'];
}