<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.__('Listing Manager', 'wpl').'</h3><p>'.__("Here you can see and manage your added listings. You can modify them, add a new listing or temporary disable them.", 'wpl').'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>__('Introduction', 'wpl'));

if(wpl_users::is_administrator())
{
    $articles  = '';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/546/" target="_blank">'.__("How to change user/agent of a listing", 'wpl').'</a></li>';

    $content = '<h3>'.__('Related KB Articles', 'wpl').'</h3><p>'.__('Here you can find some of important KB articles that answer questions related to this page. You can check this section if you faced any question on certain pages.', 'wpl').'</p><p><ul>'.$articles.'</ul></p>';
    $tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>__('KB Articles', 'wpl'));
}

// Hide Tour button
$tabs['sidebar'] = array('content'=>'');

return $tabs;