<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Sort options library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 08/11/2013
 * @package WPL
 */
class wpl_sort_options
{
	/**
	 * Minimum ID of a custom sort option
	 * @var integer
	 */
	static $sort_options_min_id = 500;

    /**
     * Gets sort options
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $kind
     * @param int $enabled
     * @param string $condition
     * @param string $output_type
     * @param bool $format_kinds
     * @return array
     */
	public static function get_sort_options($kind = '', $enabled = 1, $condition = '', $output_type = 'loadAssocList', $format_kinds = false)
	{
		if(trim($condition) == '')
		{
			$condition = "";
			
			if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
			if(trim($kind) != '') $condition .= " AND `kind` LIKE '%[$kind]%'";
			$condition .= " ORDER BY `index` ASC";
		}
		
		$query = "SELECT * FROM `#__wpl_sort_options` WHERE 1 ".$condition;
		$result = wpl_db::select($query, $output_type);

		if($format_kinds)
		{
			if($output_type == 'loadAssocList')
				foreach($result as $index => $row) $result[$index]['kind'] = self::format_kinds($row['kind']); 
			elseif($output_type == 'loadObjectList')
				foreach($result as $index => $row) $result[$index]->kind = self::format_kinds($row->kind); 
		}

		return $result;
	}
	
    /**
     * Sorts sort options
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $sort_ids
     */
	public static function sort_options($sort_ids)
	{
		$query = "SELECT `id`,`index` FROM `#__wpl_sort_options` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC";
		$options = wpl_db::select($query, 'loadAssocList');
		
		$conter = 0;
		$ex_sort_ids = explode(',', $sort_ids);
		
		foreach($ex_sort_ids as $ex_sort_id)
		{
			self::update('wpl_sort_options', $ex_sort_id, 'index', $options[$conter]["index"]);
			$conter++;
		}
	}
	
    /**
     * Updates wpl_sort_options table
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $table
     * @param int $id
     * @param string $key
     * @param string $value
     * @return boolean
     */
	public static function update($table = 'wpl_sort_options', $id, $key, $value = '')
	{
		/** first validation **/
		if(trim($table) == '' or trim($id) == '' or trim($key) == '') return false;

		/** trigger event **/
		wpl_global::event_handler('sort_options_updated', array('id'=>$id,'key'=>$value));

		return wpl_db::set($table, $id, $key, $value);
	}

	/**
	* Formats kinds to labeled arrays
	* @author Edward <edward@realtyna.com>
	* @static
	* @param string $kinds
	* @return array
	*/
	public static function format_kinds($kinds)
	{
		$result = array();
		$wpl_kinds = array();
		if(trim($kinds) == '') return $result;

		preg_match_all('/\[(\d+)\]/', $kinds, $matches);
		$kinds_array = $matches[1];
		if(!sizeof($kinds_array)) return $result;

		$wpl_kinds_query = wpl_db::select("SELECT `id`,`name` FROM `#__wpl_kinds`");
		foreach($wpl_kinds_query as $wpl_kinds_row) $wpl_kinds[(int) $wpl_kinds_row->id] = $wpl_kinds_row->name;

		foreach($kinds_array as $kind)
		{
			$kind = (int) $kind;
			if(in_array($kind, array_keys($wpl_kinds))) $result[] = $wpl_kinds[$kind];
		}

		return $result;
	}

	/**
	* Get available sort options
	* @author Edward <edward@realtyna.com>
	* @static
	* @param $kind
	* @param $column
	* @param $formatted
	* @return mixed
	*/
	public static function get_available($kind = '', $column = 'field_name', $formatted = true)
	{
		if(trim($column) == '' && !wpl_db::columns('wpl_sort_options', $column)) return null;

		$result = self::get_sort_options($kind, 0, '', 'loadAssocList');
		if(!$formatted) return $result;

		$records = array();
		
		foreach($result as $row)
		{
			$current_value = $row[$column];

			if($column == 'field_name')
			{
				/** format field_name **/
				$replace = array('/^(?:p\.)(.*)/'=>'${1}', '/(.*)(?:_si)$/'=>'${1}');
				$current_value = preg_replace(array_keys($replace), array_values($replace), $current_value);
			}

			$records[$row['id']] = $current_value;
		}

		return $records;
	}

	/**
	* Adds a sort option 
	* @author Edward <edward@realtyna.com>
	* @static
	* @param int $dbst_id
	* @param int $kind
	* @param boolean
	*/
	public static function add_sort_option($dbst_id, $kind)
	{
		$dbst_id = (int) $dbst_id;
		$units = array();

		$kind = (int) $kind;
		$kind_formatted = "[{$kind}]";

		if($dbst_id <= 0 or !wpl_db::exists($kind, 'wpl_kinds')) return false;

		$dbst_field = wpl_flex::render_sortable(wpl_flex::get_field($dbst_id, true));

		if(!$dbst_field or !intval($dbst_field->sortable)) return false;

		if(in_array(strtolower($dbst_field->type), wpl_units::get_si_unit_types())) $dbst_field->table_column .= '_si';

		if(!wpl_db::columns($dbst_field->table_name, $dbst_field->table_column)) return false;

		$sort_option = self::get_sort_options('', 0, " AND `field_name` = 'p.{$dbst_field->table_column}'", 'loadObject');

		if($sort_option)
		{
			if(strpos($sort_option->kind, $kind_formatted) !== false) return true;
			return self::update('wpl_sort_options', $sort_option->id, 'kind', $sort_option->kind . $kind_formatted) !== false;
		}

		$insert_id = self::get_insert_id();

		return wpl_db::q("INSERT INTO `#__wpl_sort_options` (`id`,`kind`,`name`,`field_name`,`enabled`,`index`) VALUES ('{$insert_id}', '{$kind_formatted}','{$dbst_field->name}','p.{$dbst_field->table_column}','1','99.00')") !== false;
	}

	/**
	* Remove a sort option
	* @author Edward <edward@realtyna.com>
	* @static
	* @param int $dbst_id
	* @param int $kind
	* @return boolean
	*/
	public static function remove_sort_option($dbst_id, $kind)
	{
		$dbst_id = (int) $dbst_id;
		$units = array();

		$kind = (int) $kind;
		$kind_formatted = "[{$kind}]";

		if($dbst_id <= 0 or !wpl_db::exists($kind, 'wpl_kinds')) return false;

		$dbst_field = wpl_flex::get_field($dbst_id, true);

		if(!$dbst_field) return false;

		if(in_array(strtolower($dbst_field->type), wpl_units::get_si_unit_types())) $dbst_field->table_column .= '_si';

		$sort_option = self::get_sort_options('', 0, " AND `field_name` = 'p.{$dbst_field->table_column}'", 'loadObject');
		
		if(!$sort_option or strpos($sort_option->kind, $kind_formatted) === false) return true;

		$new_kind = str_replace($kind_formatted, '', trim($sort_option->kind));

		if($new_kind == '') return wpl_db::delete('wpl_sort_options', $sort_option->id) !== false;
		else return self::update('wpl_sort_options', $sort_option->id, 'kind', $new_kind) !== false;
	}

	/**
	 * Get a safe ID for insert a new sort option
	 * @author Edward <edward@realtyna.com>
	 * @static
	 * @return int
	 */
	protected static function get_insert_id()
	{
		$max_id = wpl_db::get("MAX(`id`)", "wpl_sort_options", '', '', '', "`id`<'10000'");
		return max($max_id + 1, self::$sort_options_min_id + 1);
	}
}