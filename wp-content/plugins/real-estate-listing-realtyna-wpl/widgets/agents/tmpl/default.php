<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** import js codes **/
$this->_wpl_import('widgets.agents.scripts.js', true, true);
?>
<div class="wpl_agents_widget_container <?php echo ((isset($instance['data']['style']) and $instance['data']['style'] == '2') ? 'vertical' : '' ) ?> <?php echo $this->css_class; ?>">
    <?php
    foreach($wpl_profiles as $key=>$profile)
	{
        $agent_name   = (isset($profile['materials']['first_name']['value']) ? $profile['materials']['first_name']['value'] : '') ;
        $agent_l_name = (isset($profile['materials']['last_name']['value']) ? $profile['materials']['last_name']['value'] : '');
        ?>
		<div class="wpl_profile_container" id="wpl_profile_container<?php echo $profile['data']['id']; ?>" itemscope>
			<div class="wpl_profile_picture"
				<?php
				echo 'style="'.(isset($profile['profile_picture']['image_width']) ? 'width:'.$profile['profile_picture']['image_width'].'px;' : '').(isset($profile['profile_picture']['image_height']) ? 'height:'.$profile['profile_picture']['image_height'].'px;' : '').'"'; ?>>
				<div class="front">
					<?php
					if(isset($profile['profile_picture']['url']))
					{
						echo '<img itemprop="image" src="'.$profile['profile_picture']['url'].'" alt="'.$agent_name.' '.$agent_l_name.'" />';
					}
					else
					{
						echo '<div class="no_image"></div>';
					}
				?>
				</div>
				<div class="back">
					<a itemprop="url" href="<?php echo $profile['profile_link']; ?>" class="view_properties" <?php echo 'style="'.(isset($profile['profile_picture']['image_height']) ? 'line-height:'.$profile['profile_picture']['image_height'].'px;' : '').'"'; ?>><?php echo __('View properties', 'wpl'); ?></a>
				</div>
			</div>

			<div class="wpl_profile_container_title">
				<?php
					echo '<a href='.$profile['profile_link'].'><h2 class="title" itemprop="name">'.$agent_name.' '.$agent_l_name.'</h2></a>';
					if(isset($instance['data']['mailto_status']) && $instance['data']['mailto_status'] == 1)
					{
						if(isset($profile['main_email_url'])) echo '<a href="mailto:'.$profile['data']['main_email'].'"><img src="'.$profile["main_email_url"].'" alt="'.$agent_name.' '.$agent_l_name.'" /></a>';
					}
                    else
					{
						if (isset($profile['main_email_url'])) echo '<img src="' . $profile["main_email_url"] . '" alt="' . $agent_name . ' ' . $agent_l_name . '" />';
					}
				?>
			</div>
			<ul>
				<?php if(isset($profile['materials']['website']['value'])): ?>
					<li class="website" data-toggle="tooltip" title="<?php echo $profile['materials']['website']['value']; ?>">
						<a itemprop="url" href="<?php
						$urlStr = $profile['materials']['website']['value'];
						$parsed = parse_url($urlStr);
						if (empty($parsed['scheme'])) {
							$urlStr = 'http://' . ltrim($urlStr, '/');
						}
						echo $urlStr;
						?>" target="_blank"><?php echo $agent_name.' '.$agent_l_name; ?></a>
					</li>
				<?php endif; ?>
				<?php if(isset($profile['materials']['tel']['value'])): ?>
					<li itemprop="telephone" class="phone" data-toggle="tooltip" title="<?php echo $profile['materials']['tel']['value']; ?>">
						<a href="tel:<?php echo $profile['materials']['tel']['value']; ?>">
							<?php echo $profile['materials']['tel']['value']; ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if(isset($profile['materials']['mobile']['value'])): ?>
					<li itemprop="telephone" class="mobile" data-toggle="tooltip" title="<?php echo $profile['materials']['mobile']['value']; ?>">
						<a href="tel:<?php echo $profile['materials']['mobile']['value']; ?>">
							<?php echo $profile['materials']['mobile']['value']; ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if(isset($profile['materials']['fax']['value'])): ?>
					<li itemprop="faxNumber" class="fax" data-toggle="tooltip" title="<?php echo $profile['materials']['fax']['value']; ?>"></li>
				<?php endif ;?>
			</ul>
		</div>
	<?php
	}
    ?>
</div>