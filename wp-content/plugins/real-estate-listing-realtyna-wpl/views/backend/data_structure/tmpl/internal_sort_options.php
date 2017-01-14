<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.internal_sort_options_js');
?>
<div>
    <table class="widefat page">
        <thead>
            <tr>
                <th scope="col" class="manage-column"><?php echo __('Name', 'wpl'); ?></th>
                <th scope="col" class="manage-column"><?php echo __('Kinds', 'wpl'); ?></th>
                <th scope="col" class="manage-column"><?php echo __('Enabled', 'wpl'); ?></th>
                <th scope="col" class="manage-column"><?php echo __('Move', 'wpl'); ?></th>
            </tr>
        </thead>
        <tbody class="sortable_sort_options">
            <?php foreach($this->sort_options as $option): ?>
                <tr id="items_row_<?php echo $option['id']; ?>">
                    <td>
                        <input type="text" value="<?php echo __($option['name'], 'wpl'); ?>" id="wpl_sort_option_name<?php echo $option['id']; ?>" onchange="wpl_save_sort_option(<?php echo $option['id']; ?>);" />
                        <span id="wpl_sort_option_ajax_loader<?php echo $option['id']; ?>"></span>
                    </td>
                    <td class="manager-wp"><?php echo implode('/', $option['kind']); ?></td>
                    <td class="manager-wp wpl_sort_options_manager">
                        <span class="action-btn <?php echo $option['enabled'] == 1 ? "icon-enabled" : "icon-disabled"; ?>" onclick="wpl_sort_options_enabled_change(<?php echo $option['id']; ?>);" id="wpl_ajax_flag_options_<?php echo $option['id']; ?>"></span>
                        <span class="wpl_ajax_loader" id="wpl_ajax_loader_options_<?php echo $option['id']; ?>"></span>
                    </td>
                    <td class="manager-wp">
                        <span class="action-btn icon-move" id="sort_move_<?php echo $option['id']; ?>"></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>