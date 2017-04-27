<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$description_column = 'field_308';
if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, $this->kind)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

// Membership ID of current user
$current_user_membership_id = wpl_users::get_user_membership();

foreach($this->wpl_properties as $key=>$property)
{
    if($key == 'current') continue;

    /** unset previous property **/
    unset($this->wpl_properties['current']);

    /** set current property **/
    $this->wpl_properties['current'] = $property;

    if(isset($property['materials']['bedrooms']['value']) and ($_bedrooms = intval($property['materials']['bedrooms']['value']))) $room = sprintf('<div class="bedroom">%d<span>%s</span></div>', $_bedrooms, __(wpl_global::pluralize($_bedrooms, "Bedroom"), "wpl"));
    elseif(isset($property['materials']['rooms']['value']) and ($_rooms = intval($property['materials']['rooms']['value']))) $room = sprintf('<div class="room">%d<span>%s</span></div>', $_rooms, __(wpl_global::pluralize($_rooms, "Room"), "wpl"));
    else $room = '';

    $bathroom = (isset($property['materials']['bathrooms']['value']) and ($_bathrooms = floatval($property['materials']['bathrooms']['value']))) ? sprintf('<div class="bathroom">%s<span>%s</span></div>', $_bathrooms, __(wpl_global::pluralize($_bathrooms, "Bathroom"), "wpl")) : '';

    $parking = (isset($property['materials']['f_150']['values'][0]) and ($_parkings = intval($property['materials']['f_150']['values'][0]))) ? sprintf('<div class="parking">%d<span>%s</span></div>', $_parkings, __(wpl_global::pluralize($_parkings, "Parking"), "wpl")) : '';

    $pic_count = (isset($property['raw']['pic_numb']) and ($_pic_count = intval($property['raw']['pic_numb']))) ? sprintf('<div class="pic_count">%d<span>%s</span></div>', $_pic_count, __(wpl_global::pluralize($_pic_count, "Picture"), "wpl")) : '';
	
	$living_area = isset($property['materials']['living_area']['value']) ? explode(' ', $property['materials']['living_area']['value']) : (isset($property['materials']['lot_area']['value']) ? explode(' ', $property['materials']['lot_area']['value']): '' );
	$build_up_area = '<div class="built_up_area">'.$living_area[0].'<span>'.$living_area[1].'</span></div>';
	
	$property_price = (isset($property['materials']['price']['value']) and intval(preg_replace("/[^0-9]/", "", $property['materials']['price']['value']))) ? $property['materials']['price']['value'] : '&nbsp;';
    
    $description = stripslashes(strip_tags($property['raw'][$description_column]));
    $cut_position = strrpos(substr($description, 0, 400), '.', -1);
    if(!$cut_position) $cut_position = 399;
    ?>
	<div class="wpl-column">
		<div class="wpl_prp_cont wpl_prp_cont_old <?php echo ((isset($this->property_css_class) and in_array($this->property_css_class, array('row_box', 'grid_box'))) ? $this->property_css_class : ''); ?>" id="wpl_prp_cont<?php echo $property['data']['id']; ?>" itemscope itemtype="https://schema.org/TradeAction">
			<div class="wpl_prp_top">
				<div class="wpl_prp_top_boxes front">
					<?php wpl_activity::load_position('wpl_property_listing_image', array('wpl_properties'=>$this->wpl_properties)); ?>
				</div>
				<div class="wpl_prp_top_boxes back">
					<a itemprop="url" id="prp_link_id_<?php echo $property['data']['id']; ?>" href="<?php echo $property['property_link']; ?>" class="view_detail"><?php echo __('More Details', 'wpl'); ?></a>
				</div>
			</div>
			<div class="wpl_prp_bot">
				<?php
				echo '<a id="prp_link_id_'.$property['data']['id'].'_view_detail" href="'.$property['property_link'].'" class="view_detail" title="'.$property['property_title'].'">
				  <h3 class="wpl_prp_title" itemprop="name">'.$property['property_title'].'</h3></a>';
                
                $location_visibility = wpl_property::location_visibility($property['data']['id'], $property['data']['kind'], $current_user_membership_id);
				echo '<h4 class="wpl_prp_listing_location" itemprop="location" itemscope itemtype="http://schema.org/Place"><span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="addressLocality">'.($location_visibility === true ? $property['location_text'] : $location_visibility).'</span></span></h4>';
				?>
				<div class="wpl_prp_listing_icon_box"><?php echo $room . $bathroom . $parking . $pic_count . $build_up_area; ?></div>
				<div class="wpl_prp_desc" itemprop="description"><?php echo substr($description, 0, $cut_position + 1); ?></div>
			</div>
			<div class="price_box">
				<span itemprop="price" content="<?php echo $property_price; ?>"><?php echo $property_price; ?></span>
			</div>
		</div>
	</div>
    <?php
}