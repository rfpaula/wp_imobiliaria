<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_io_cmd_get_property extends wpl_io_cmd_base
{
    private $where = array();
    private $order_by = "id";
    private $order = "DESC";
    private $built;

    /**
     * This method is the main method of each commands
     * @return mixed
     */
    public function build()
    {
        if(isset($this->params['sort_item']) && trim($this->params['sort_item']) != '')
        {
            $this->order_by = $this->params['sort_item'];
        }
        
        if(isset($this->params['sort_method']) && trim($this->params['sort_method']) != '')
        {
            $this->order = $this->params['sort_method'];
        }

        $this->where = wpl_addon_mobile_application::create_query_where($this->params);
        $this->where['sf_select_confirmed'] = '1';
        $this->where['sf_select_finalized'] = '1';
        $this->where['sf_select_deleted'] = '0';

        $model = new wpl_property();
        $model->start(0, 1, $this->order_by, $this->order, $this->where);
        $model->query();
        $result = $model->search();
        $result = wpl_property::get_property_raw_data($result[key($result)]->id);
        $user = wpl_users::get_user($result['user_id']);
        $price_unit = wpl_units::get_default_unit(4)['name'];
        $area_unit = wpl_units::get_default_unit(2)['name'];

        $image = $this->get_profile_image($result['user_id']);
        $this->built = array('property_show_sections'=>array(

            array(
                'section_type'=>'long_text',
                'title'=>'DESCRIPTION',
                'content'=>strip_tags(stripslashes($result['field_308'])),
                'read_more_is_enabled'=>true,
                'number_of_shown_characters'=>500
            ),
            array(
                'section_type'=>'share_content',
                'content'=>$result['field_313'].'\n'.wpl_property::get_property_link($result),
            ),
            array(
                'section_type'=>'string_list',
                'title'=>'FACTS',
                'content'=>array(
                    __('Lot Area', 'wpl').': '.$result['lot_area'].' '.$area_unit,
                    __('Price', 'wpl').': '.$price_unit.$result['price'],
                    __('Listing ID', 'wpl').': '.$result['id'],
                )
            ),
            array(
                'section_type'=>'map_view',
                'content'=>array(
                    'lat'=>$result['googlemap_lt'],
                    'lng'=>$result['googlemap_ln'],
                    'zoom'=>15
                )
            ),
            array(
                'section_type'=>'agent',
                'title'=>'GET_MORE_INFO',
                'content'=>array(
                    array(
                        'agent_name'=>$user->data->display_name,
                        'description_1'=>$user->data->wpl_data->company_name,
                        'description_2'=>'',
                        'image'=>$image,
                        'is_call_button_enabled'=>true,
                        'call_button_text'=>'CALL',
                        'call_number'=>$user->data->wpl_data->tel
                    ),
                )
            ),
            array(
                'section_type'=>'form',
                'url'=>array($this->generate_command_url('contact_agent', wpl_request::getVar('public_key'), wpl_request::getVar('private_key'), array('id'=>$result['id'], 'user_id'=>$result['user_id']))),
                'content'=>array(
                    array(
                        'field_type'=>'text',
                        'placeholder'=>'FULL_NAME',
                        'column_name'=>'fullname',
                    ),
                    array(
                        'field_type'=>'number',
                        'placeholder'=>'PHONE_NUMBER',
                        'column_name'=>'phone',
                    ),
                    array(
                        'field_type'=>'email',
                        'placeholder'=>'EMAIL',
                        'column_name'=>'email',
                    ),
                    array(
                        'field_type'=>'textarea',
                        'placeholder'=>'MESSAGE',
                        'column_name'=>'message',
                    ),
                    array(
                        'field_type'=>'button',
                        'placeholder'=>'SUBMIT',
                    ),
                )
            )
        ));

        return $this->built;
    }

    /**
     * Data validation
     * @return boolean
     */
    public function validate()
    {
        return true;
    }

    /**
     * Getting agent profile image
     * @param $user_id
     * @return null|string
     */
    private function get_profile_image($user_id)
    {
        $wpl_user = wpl_users::full_render($user_id, wpl_users::get_pshow_fields(), NULL, array(), true);
        $sex = $wpl_user['data']['sex'] == 0 ? 'male' : 'female';

        $params                   = array();
        $params['image_parentid'] = $user_id;
        $params['image_name']     = isset($wpl_user['profile_picture']['name']) ? $wpl_user['profile_picture']['name'] : '_' . $sex . '.png';
        $picture_path             = isset($wpl_user['profile_picture']['path']) ? $wpl_user['profile_picture']['path'] : '';
        
        if(trim($picture_path) == '')
        {
            $picture_path = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'membership' .DS. $sex .'.jpg';
        }
        
        return wpl_images::create_profile_images($picture_path, 160, 160, $params);
    }
}