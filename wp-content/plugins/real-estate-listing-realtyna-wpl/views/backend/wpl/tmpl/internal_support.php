<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="side-4 side-support" id="wpl_dashboard_side_support">
    <div class="panel-wp">
        <h3><?php echo __('Documentation / Support', 'wpl'); ?></h3>
        <div class="panel-body">
            <p>
                <?php echo __('Join our mailing list for tips, tricks and sale offers.', 'wpl'); ?>
            </p>
            <form onsubmit="wpl_add_to_mailing_list(); return false;">
                <input type="text" name="wpl_mailing_list" placeholder="<?php __('Your email', 'wpl'); ?>" value="<?php echo wpl_global::get_wp_option('admin_email'); ?>" id="wpl_mailing_list" />
                <button type="submit" class="wpl-button button-1" id="wpl_mailing_list_button"><?php echo __('join'); ?></button>
            </form>
            <div class="wpl_mailing_list_message"><div class="wpl_show_message"></div></div>
        </div>
        <div class="panel-body">
            <p>
                <strong><a target="_blank" href="http://wpl.realtyna.com/articles/wpl-translation-offer"> -- <?php echo __('WPL Translation Offer', 'wpl'); ?> -- </a></strong>
            </p>
        	<p>
				<?php echo __('You can download', 'wpl'); ?>&nbsp;<a href="http://wpl.realtyna.com/wassets/wpl-manual.pdf" target="_blank"><?php echo __('WPL Manual', 'wpl'); ?></a>
                <?php echo __('and check', 'wpl'); ?>&nbsp;<a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/List/Index/28/wpl---wordpress-property-listing" target="_blank"><?php echo __('WPL Knowledge base', 'wpl'); ?></a>.
            </p>
            <p>
                <?php echo __("Also you can find WPL KB articles in \"KB Articles\" section below and simply search on them by typing a keyword in filter textbox!", 'wpl'); ?>
            </p>
            <p>
                <?php echo __("If you can't find an answer to your question in WPL Manual or Knowledge Base, please", 'wpl'); ?>&nbsp;<a href="https://support.realtyna.com/index.php?/Tickets/Submit/RenderForm/18" target="_blank"><?php echo __('open a Support Ticket', 'wpl'); ?></a>&nbsp;<?php echo __('to ask your question.  We will respond as soon as possible with an answer.', 'wpl'); ?>
			</p>
        </div>
    </div>
</div>