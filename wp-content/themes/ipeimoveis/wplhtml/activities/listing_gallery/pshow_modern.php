<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$this->property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

/** get image params **/
$this->image_width = isset($params['image_width']) ? $params['image_width'] : 360;
$this->image_height = isset($params['image_height']) ? $params['image_height'] : 285;
$this->image_class = isset($params['image_class']) ? $params['image_class'] : '';
$this->category = (isset($params['category']) and trim($params['category']) != '') ? $params['category'] : '';
$this->autoplay = (isset($params['autoplay']) and trim($params['autoplay']) != '') ? $params['autoplay'] : 1;
$this->resize = (isset($params['resize']) and trim($params['resize']) != '') ? $params['resize'] : 1;
$this->rewrite = (isset($params['rewrite']) and trim($params['rewrite']) != '') ? $params['rewrite'] : 0;
$this->watermark = (isset($params['watermark']) and trim($params['watermark']) != '') ? $params['watermark'] : 1;
$this->thumbnail = (isset($params['thumbnail']) and trim($params['thumbnail']) != '') ? $params['thumbnail'] : 1;
$this->thumbnail_width = isset($params['thumbnail_width']) ? $params['thumbnail_width'] : 100;
$this->thumbnail_height = isset($params['thumbnail_height']) ? $params['thumbnail_height'] : 80;
$this->thumbnail_numbers = isset($params['thumbnail_numbers']) ? $params['thumbnail_numbers'] : 20;

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();

// Filter images by category
if(trim($this->category) != '') $raw_gallery = $this->categorize($raw_gallery, $this->category);

$this->gallery = wpl_items::render_gallery($raw_gallery, wpl_property::get_blog_id($this->property_id));

$js[] = (object) array('param1'=>'lightslider.js', 'param2'=>'packages/light_slider/js/lightslider.min.js');
$js[] = (object) array('param1'=>'lightGallery.js', 'param2'=>'packages/light_gallery/js/lightGallery.min.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

$css[] = (object) array('param1'=>'lightslider.css', 'param2'=>'packages/light_slider/css/lightslider.min.css');
$css[] = (object) array('param1'=>'lightGallery.css', 'param2'=>'packages/light_gallery/css/lightGallery.css');
foreach($css as $style) wpl_extensions::import_style($style);

/** import js/css codes **/
$this->_wpl_import($this->tpl_path.'.scripts.pshow_modern', true, false);

/** Rodrigo: adicionando script de lazy load **/
// https://afarkas.github.io/lazysizes/
echo '<script type="text/javascript" src="' .get_template_directory_uri() . '/js/lazysizes.min.js" async=""></script>';
/** Fim da altaracao **/
?>

<div class="wpl-gallery-pshow-wp wpl-details-gallery-loading" id="wpl_gallery_wrapper-<?php echo $this->activity_id; ?>">

    <ul class="wpl-gallery-pshow" id="wpl_gallery_container<?php echo $this->property_id; ?>">

        <?php
        if(!count($this->gallery))
        {
            echo '<li class="gallery_no_image"></li>';
        }
        else
        {
            foreach($this->gallery as $image)
            {
                $image_url = $image['url'];
                $image_thumbnail_url = $image['url'];
                $original_image_url = $image['url'];

                if(isset($image['item_extra2'])) $image_alt = $image['item_extra2'];
                else $image_alt = $wpl_properties['current']['raw']['meta_keywords'];

                if($this->resize and $this->image_width and $this->image_height and $image['category'] != 'external')
                {
                    /** set resize method parameters **/
                    $params = array();
                    $params['image_name'] = $image['raw']['item_name'];
                    $params['image_parentid'] = $image['raw']['parent_id'];
                    $params['image_parentkind'] = $image['raw']['parent_kind'];
                    $params['image_source'] = $image['path'];
                    
                    /** resize image if does not exist and add watermark **/
                    $image_url = wpl_images::create_gallery_image($this->image_width, $this->image_height, $params, $this->watermark, $this->rewrite);
                    $image_thumbnail_url = wpl_images::create_gallery_image($this->thumbnail_width, $this->thumbnail_height, $params, 0, $this->rewrite);
                    
                    /** Watermark original image **/
                    if($this->watermark) $original_image_url = wpl_images::watermark_original_image($params);
                }
                ?>
                <li id="wpl-gallery-img-<?php echo $image['raw']['id']; ?>" data-thumb="<?php echo $image_thumbnail_url; ?>" data-src="<?php echo $image_url; ?>" data-hover-title="<?php echo __('Clique para ver a galeria', 'wpl'); ?>" style="position:absolute;opacity:0;">
                    <span>
                        <img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" >
                    </span>
                </li>
                <?php
            }
        }
        ?>

    </ul>

</div>
