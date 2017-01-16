<?php

class PP_Tabs_Shortcode {

	function __construct() {

		add_shortcode('pp-tabs', array($this, 'output_field'));

	}

	function output_field($atts) {
		global $ultimatemember;

		$a = shortcode_atts(array(
			'field' => '',
			'label' => '0',
		), $atts);

		if (strtolower($a['field']) == "id") {
			return um_profile_id();
		}

		um_fetch_user(um_profile_id());

		$key       = $a['field'];
		$showlabel = $a['label'];

		if (um_user($key)) {

			$output = null;

			$data = $ultimatemember->fields->get_field($key);
			extract($data);

			if (!isset($data['type'])) {
				return;
			}

			if (isset($data['in_group']) && $data['in_group'] != '' && $rule != 'group') {
				return;
			}

			if (in_array($type, array('block', 'shortcode', 'spacing', 'divider', 'group'))) {

			} else {
				if (!$ultimatemember->fields->field_value($key, $default, $data)) {
					return;
				}

			}

			if (!um_can_view_field($data)) {
				return;
			}

			if (!um_field_conditions_are_met($data)) {
				return;
			}

			switch ($type) {

			/* Default */
			default:

				if ($showlabel == '1') {
					$output .= $ultimatemember->fields->field_label($label, $key, $data);
				}

				$res = stripslashes($ultimatemember->fields->field_value($key, $default, $data));

				$output .= $res;

				break;

			/* HTML */
			case 'block':
				$output .= '<div class="um-field' . $classes . '"' . $conditional . ' data-key="' . $key . '">
									<div class="um-field-block">' . $content . '</div>
								</div>';
				break;

			/* Shortcode */
			case 'shortcode':

				$content = str_replace('{profile_id}', um_profile_id(), $content);

				$output .= '<div class="um-field' . $classes . '"' . $conditional . ' data-key="' . $key . '">
									<div class="um-field-shortcode">' . do_shortcode($content) . '</div>
								</div>';
				break;

			/* Gap/Space */
			case 'spacing':
				$output .= '<div class="um-field um-field-spacing' . $classes . '"' . $conditional . ' style="height: ' . $spacing . '"></div>';
				break;

			/* A line divider */
			case 'divider':
				$output .= '<div class="um-field um-field-divider' . $classes . '"' . $conditional . ' style="border-bottom: ' . $borderwidth . 'px ' . $borderstyle . ' ' . $bordercolor . '">';
				if ($divider_text) {
					$output .= '<div class="um-field-divider-text"><span>' . $divider_text . '</span></div>';
				}
				$output .= '</div>';
				break;

			/* Rating */
			case 'rating':

				$output .= '<div class="um-field' . $classes . '"' . $conditional . ' data-key="' . $key . '">';

				if (isset($data['label'])) {
					$output .= $ultimatemember->fields->field_label($label, $key, $data);
				}

				$output .= '<div class="um-field-area">';
				$output .= '<div class="um-field-value">
											<div class="um-rating-readonly um-raty" id="' . $key . '" data-key="' . $key . '" data-number="' . $data['number'] . '" data-score="' . $ultimatemember->fields->field_value($key, $default, $data) . '"></div>
										</div>';
				$output .= '</div>';

				$output .= '</div>';

				break;

			}

			// Custom filter for field output
			if (isset($ultimatemember->fields->set_mode)) {
				$output = apply_filters("um_{$key}_form_show_field", $output, $ultimatemember->fields->set_mode);
			}

			return $output;

		} else {
			return '';
		}

	}

}

?>