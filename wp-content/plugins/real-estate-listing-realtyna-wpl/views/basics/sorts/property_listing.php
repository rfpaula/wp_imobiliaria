<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** include library **/
_wpl_import('libraries.sort_options');

$result = NULL;

$type = isset($params['type']) ? $params['type'] : 1; # 1 == ul and 0 == selectbox
$kind = isset($params['kind']) ? $params['kind'] : 0;
$return_array = isset($params['return_array']) ? $params['return_array'] : 0;

$sort_options = wpl_sort_options::get_sort_options($kind, 1);

$result_array = array();
foreach($sort_options as $sort_option)
{
	$result_array['sort_options'][] = array
	(
		'field_name' => $sort_option['field_name'],
		'url' => '',
		'active' => $this->orderby == $sort_option['field_name'] ? 1 : 0,
		'order' => ($this->order == 'DESC' and $this->orderby == $sort_option['field_name']) ? 'ASC' : 'DESC',
		'name' => $sort_option['name']
	);
}

$html = '';
if($type == 0)
{
	$html .= '<select class="wpl_plist_sort" onchange="wpl_page_sortchange(this.value);">';
	
	foreach($sort_options as $sort_option)
	{
        if(in_array($sort_option['field_name'], array('ptype_adv', 'ltype_adv')))
        {
            $searched_types = array();
            if($sort_option['field_name'] == 'ptype_adv')
            {
                $types = wpl_global::get_property_types();
                $column = 'property_type';
                
                $multiple_types = explode(',', wpl_request::getVar('sf_multiple_property_type', ''));
                if(count($multiple_types) == 1 and trim($multiple_types[0]) == '') $multiple_types = array();
                
                $single_type = wpl_request::getVar('sf_select_property_type', '');
                $searched_types = array_unique(array_merge($multiple_types, array($single_type)));
            }
            elseif($sort_option['field_name'] == 'ltype_adv')
            {
                $types = wpl_global::get_listings();
                $column = 'listing';
                
                $multiple_types = explode(',', wpl_request::getVar('sf_multiple_listing', ''));
                if(count($multiple_types) == 1 and trim($multiple_types[0]) == '') $multiple_types = array();
                
                $single_type = wpl_request::getVar('sf_select_listing', '');
                $searched_types = array_unique(array_merge($multiple_types, array($single_type)));
            }
            
            if(count($searched_types) == 1 and trim($searched_types[0]) == '') $searched_types = array();
            
            foreach($types as $type)
            {
                if(is_array($searched_types) and count($searched_types) > 0 and !in_array($type['id'], $searched_types)) continue;
                
                $html .= '<option value="wplorderby='.urlencode($sort_option['field_name'].':'.$type['id']).'&amp;wplorder=ASC" '.(($this->orderby == "(p.`".$column."` != '".$type['id']."'), p.`".$column."`") ? 'selected="selected"' : '').'>'.sprintf(__('%s first', 'wpl'), __(wpl_global::pluralize(2, $type['name']), 'wpl')).'</option>';
                $html .= '<option value="wplorderby='.urlencode($sort_option['field_name'].':'.$type['id']).'&amp;wplorder=DESC" '.(($this->orderby == "(p.`".$column."` = '".$type['id']."'), p.`".$column."`") ? 'selected="selected"' : '').'>'.sprintf(__('%s last', 'wpl'), __(wpl_global::pluralize(2, $type['name']), 'wpl')).'</option>';
            }
        }
        else
        {
            $html .= '<option value="wplorderby='.urlencode($sort_option['field_name']).'&amp;wplorder=ASC" '.(($this->orderby == $sort_option['field_name'] and $this->order == 'ASC') ? 'selected="selected"' : '').'>'.__($sort_option['name'], 'wpl').' '.__('Ascending', 'wpl').'</option>';
            $html .= '<option value="wplorderby='.urlencode($sort_option['field_name']).'&amp;wplorder=DESC" '.(($this->orderby == $sort_option['field_name'] and $this->order == 'DESC') ? 'selected="selected"' : '').'>'.__($sort_option['name'], 'wpl').' '.__('Descending', 'wpl').'</option>';
        }
	}
	
	$html .= '</select>';
}
elseif($type == 1)
{
	$html .= '<ul>';
	$sort_type = '';
    
	foreach($sort_options as $sort_option)
	{
        if(in_array($sort_option['field_name'], array('ptype_adv', 'ltype_adv')))
        {
            $searched_types = array();
            if($sort_option['field_name'] == 'ptype_adv')
            {
                $types = wpl_global::get_property_types();
                $column = 'property_type';
                
                $multiple_types = explode(',', wpl_request::getVar('sf_multiple_property_type', ''));
                if(count($multiple_types) == 1 and trim($multiple_types[0]) == '') $multiple_types = array();
                
                $single_type = wpl_request::getVar('sf_select_property_type', '');
                $searched_types = array_unique(array_merge($multiple_types, array($single_type)));
            }
            elseif($sort_option['field_name'] == 'ltype_adv')
            {
                $types = wpl_global::get_listings();
                $column = 'listing';
                
                $multiple_types = explode(',', wpl_request::getVar('sf_multiple_listing', ''));
                if(count($multiple_types) == 1 and trim($multiple_types[0]) == '') $multiple_types = array();
                
                $single_type = wpl_request::getVar('sf_select_listing', '');
                $searched_types = array_unique(array_merge($multiple_types, array($single_type)));
            }
            
            if(count($searched_types) == 1 and trim($searched_types[0]) == '') $searched_types = array();
            
            foreach($types as $type)
            {
                if(is_array($searched_types) and count($searched_types) > 0 and !in_array($type['id'], $searched_types)) continue;
                
                $class = "wpl_plist_sort";
                
                if($this->orderby == "(p.`".$column."` ".($this->order == 'ASC' ? '!' : '')."= '".$type['id']."'), p.`".$column."`") $class = "wpl_plist_sort wpl_plist_sort_active";
                $order = $order_label = 'ASC';
                
                $html .= '<li><div class="'.$class;

                if($this->orderby == "(p.`".$column."` ".($this->order == 'ASC' ? '!' : '')."= '".$type['id']."'), p.`".$column."`")
                {
                    if($this->order == "ASC") $sort_type = 'sort_up';
                    else $sort_type = 'sort_down';

                    $order = ($this->order == 'ASC' ? 'DESC' : 'ASC');
                    $order_label = $this->order;
                    
                    $html .= ' '.$sort_type;
                }

                $html .= '" onclick="wpl_page_sortchange(\'wplorderby='.urlencode($sort_option['field_name'].':'.$type['id']).'&amp;wplorder='.$order.'\');">'.($order_label == "ASC" ? sprintf(__('%s first', 'wpl'), __(wpl_global::pluralize(2, $type['name']), 'wpl')) : sprintf(__('%s last', 'wpl'), __(wpl_global::pluralize(2, $type['name']), 'wpl')));
                $html .= '</div></li>';
            }
        }
        else
        {
            $class = "wpl_plist_sort";
            
            if($this->orderby == $sort_option['field_name']) $class = "wpl_plist_sort wpl_plist_sort_active";
            $order = ($this->order == "ASC" ? "DESC" : "ASC");

            $html .= '<li><div class="'.$class;

            if($this->orderby == $sort_option['field_name'])
            {
                if($this->order == "ASC") $sort_type = 'sort_up';
                else $sort_type = 'sort_down';

                $html .= ' '.$sort_type;
            }

            $html .= '" onclick="wpl_page_sortchange(\'wplorderby='.urlencode($sort_option['field_name']).'&amp;wplorder='.$order.'\');">'.__($sort_option['name'], 'wpl');
            $html .= '</div></li>';
        }
	}
	
	$html .= '</ul>';
}

$result_array['html'] = $html;

if($return_array) $result = $result_array;
else $result = $html;