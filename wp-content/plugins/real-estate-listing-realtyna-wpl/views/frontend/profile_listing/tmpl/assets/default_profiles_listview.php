<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl_sort_options_container">
    <div class="wpl_sort_options_container_title"><?php echo __('Sort Option', 'wpl') ?></div>
    <?php echo $this->model->generate_sorts(); ?>
    <?php if($this->property_css_class_switcher): ?>
    <div class="wpl_list_grid_switcher <?php if($this->switcher_type == "icon+text") echo 'wpl-list-grid-switcher-icon-text'; ?>">
        <div id="grid_view" class="grid_view <?php if($this->property_css_class == 'grid_box') echo 'active'; ?>">
            <?php if($this->switcher_type == "icon+text") echo '<span>'.__('Grid', 'wpl').'</span>'; ?>
        </div>
        <div id="list_view" class="list_view <?php if($this->property_css_class == 'row_box') echo 'active'; ?>">
            <?php if($this->switcher_type == "icon+text") echo '<span>'.__('List', 'wpl').'</span>'; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="wpl-row wpl-expanded <?php if($this->property_css_class == "grid_box") echo "wpl-small-up-1 wpl-medium-up-2 wpl-large-up-".$this->profile_columns; ?>  wpl_profile_listing_profiles_container clearfix">
  <?php echo $this->profiles_str; ?>
</div>

<?php if($this->wplpagination != 'scroll'): ?>
<div class="wpl_pagination_container">
    <?php echo $this->pagination->show(); ?>
</div>
<?php endif; ?>
