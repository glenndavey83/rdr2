<?php

defined( 'ABSPATH' ) || die();

/**
 * Adds marker iterator for advanced google maps
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.1
 * @package     artbees
 */

vc_add_shortcode_param('gmap_marker', 'mk_gmap_marker_param_field');

function mk_gmap_marker_param_field($settings, $value) {
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $output = '';
    $uniqeID = uniqid();
    if ( base64_decode($value, true) === false ) {
        $locations = (!empty($value)) ? json_decode( urldecode($value), true) : [];
    } else {
        $locations = (!empty($value)) ? json_decode( urldecode( base64_decode($value) ), true) : [];
    }


    $output.= '<div class="gmap-marker-popup">
    	<h4>'.__('New Address', 'jupiter-donut').'</h4>

    	<input type="hidden" name="uniqid" id="marker-uniqid" value="" />

        <div class="mk-popup-field">
            <label for="title">'.__('Address Title', 'jupiter-donut').'</label>
            <input type="text" name="title" />
        </div>

    	<div class="mk-popup-field">
	    	<label for="latitude">'.__('Latitude', 'jupiter-donut').'</label>
	    	<input type="text" name="latitude" />
    	</div>

    	<div class="mk-popup-field">
	    	<label for="longitude">'.__('Longitude', 'jupiter-donut').'</label>
	    	<input type="text" name="longitude" />
    	</div>

    	<div class="mk-popup-field">
	    	<label for="address">'.__('Full Address Text (Shown In Tooltip)', 'jupiter-donut').'</label>
	    	<input type="text" name="address" />
    	</div>

    	<div class="mk-popup-field">
    		<label for="marker_icon">'.__('Upload Marker Icon', 'jupiter-donut').'</label>
	    	<div class="upload-option">
			    	<input class="mk-upload-url vc-mk-upload-url" type="text" id="'.$uniqeID.'" name="marker_icon" value="">
			    	<a class="option-upload-button secondary-button thickbox" id="'.$uniqeID.'_button" href="#">'.__('Upload', 'jupiter-donut').'</a>
			    	<span id="'.$uniqeID.'-preview" class="show-upload-image" alt=""><img src="" title=""></span>
	    	</div>
    	</div>

    	<div class="mk-popup-buttons">
    	<a href="#" id="mk-popup-submit-btn" class="primary-button green-button">'.__('Save Changes', 'jupiter-donut').'</a>
    	<a href="#" id="mk-popup-cancel-btn" class="secondary-button" style="padding:14px 30px">'.__('Close', 'jupiter-donut').'</a>
    	</div>


    </div>';

    $output.= '<div class="gmap-marker-option" id="gmap-marker-option-' . $uniqeID . '">';

    $output.= '<ul class="gmap-marker-locations">';

        if(empty($locations)) {
            $output.= '<li class="temp" style="display:none">';
                $output.= '<span></span>'; // Address
                $output.= '<a href="#" class="gmap-delete-btn">'.__('Delete', 'jupiter-donut').'</a>';
                $output.= '<a href="#" class="gmap-edit-btn">'.__('Edit', 'jupiter-donut').'</a>';
            $output.= '</li>';

        } else {
            foreach ($locations as $mark) {
                $output.= '<li data-id="'.$mark['uniqid'].'">';
                    $output.= '<span>'. $mark['title'].'</span>'; // Address
                    $output.= '<a href="#" class="gmap-delete-btn">'.__('Delete', 'jupiter-donut').'</a>';
                    $output.= '<a href="#" class="gmap-edit-btn">'.__('Edit', 'jupiter-donut').'</a>';
                $output.= '</li>';
            }
        }

    $output.= '</ul>';

    $output .= '<a class="gmap-new-loaction-btn" href="#">'.__('Add New Location', 'jupiter-donut').'</a>';

	$output .= '<input type="hidden" class="wpb_vc_param_value gmap-marks-collector ' . $param_name . ' ' . $type . '" value=\'' . $value . '\' name="' . $param_name . '" />';

	$output.= '</div>';

    $output.= '<script type="text/javascript">

    	(function($) {
	        mk_gmap_iterator("'.$uniqeID.'");
	        mk_upload_option("'.$uniqeID.'");
        })(jQuery);

    </script>';

    return $output;
}
