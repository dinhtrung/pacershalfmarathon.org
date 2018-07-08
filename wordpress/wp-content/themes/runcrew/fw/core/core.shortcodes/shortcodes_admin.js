// Init scripts
jQuery(document).ready(function(){
	"use strict";
	
	// Settings and constants
	RUNCREW_STORAGE['shortcodes_delimiter'] = ',';		// Delimiter for multiple values
	RUNCREW_STORAGE['shortcodes_popup'] = null;		// Popup with current shortcode settings
	RUNCREW_STORAGE['shortcodes_current_idx'] = '';	// Current shortcode's index
	RUNCREW_STORAGE['shortcodes_tab_clone_tab'] = '<li id="runcrew_shortcodes_tab_{id}" data-id="{id}"><a href="#runcrew_shortcodes_tab_{id}_content"><span class="iconadmin-{icon}"></span>{title}</a></li>';
	RUNCREW_STORAGE['shortcodes_tab_clone_content'] = '';

	// Shortcode selector - "change" event handler - add selected shortcode in editor
	jQuery('body').on('change', ".sc_selector", function() {
		"use strict";
		RUNCREW_STORAGE['shortcodes_current_idx'] = jQuery(this).find(":selected").val();
		if (RUNCREW_STORAGE['shortcodes_current_idx'] == '') return;
		var sc = runcrew_clone_object(RUNCREW_SHORTCODES_DATA[RUNCREW_STORAGE['shortcodes_current_idx']]);
		var hdr = sc.title;
		var content = "";
		try {
			content = tinyMCE.activeEditor ? tinyMCE.activeEditor.selection.getContent({format : 'raw'}) : jQuery('#wp-content-editor-container textarea').selection();
		} catch(e) {};
		if (content) {
			for (var i in sc.params) {
				if (i == '_content_') {
					sc.params[i].value = content;
					break;
				}
			}
		}
		var html = (!runcrew_empty(sc.desc) ? '<p>'+sc.desc+'</p>' : '')
			+ runcrew_shortcodes_prepare_layout(sc);


		// Show Dialog popup
		RUNCREW_STORAGE['shortcodes_popup'] = runcrew_message_dialog(html, hdr,
			function(popup) {
				"use strict";
				runcrew_options_init(popup);
				popup.find('.runcrew_options_tab_content').css({
					maxHeight: jQuery(window).height() - 300 + 'px',
					overflow: 'auto'
				});
			},
			function(btn, popup) {
				"use strict";
				if (btn != 1) return;
				var sc = runcrew_shortcodes_get_code(RUNCREW_STORAGE['shortcodes_popup']);
				if (tinyMCE.activeEditor) {
					if ( !tinyMCE.activeEditor.isHidden() )
						tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, sc );
					//else if (typeof wpActiveEditor != 'undefined' && wpActiveEditor != '') {
					//	document.getElementById( wpActiveEditor ).value += sc;
					else
						send_to_editor(sc);
				} else
					send_to_editor(sc);
			});

		// Set first item active
		jQuery(this).get(0).options[0].selected = true;

		// Add new child tab
		RUNCREW_STORAGE['shortcodes_popup'].find('.runcrew_shortcodes_tab').on('tabsbeforeactivate', function (e, ui) {
			if (ui.newTab.data('id')=='add') {
				runcrew_shortcodes_add_tab(ui.newTab);
				e.stopImmediatePropagation();
				e.preventDefault();
				return false;
			}
		});

		// Delete child tab
		RUNCREW_STORAGE['shortcodes_popup'].find('.runcrew_shortcodes_tab > ul').on('click', '> li+li > a > span', function (e) {
			var tab = jQuery(this).parents('li');
			var idx = tab.data('id');
			if (parseInt(idx) > 1) {
				if (tab.hasClass('ui-state-active')) {
					tab.prev().find('a').trigger('click');
				}
				tab.parents('.runcrew_shortcodes_tab').find('.runcrew_options_tab_content').eq(idx).remove();
				tab.remove();
				e.preventDefault();
				return false;
			}
		});

		return false;
	});

});



// Return result code
//------------------------------------------------------------------------------------------
function runcrew_shortcodes_get_code(popup) {
	RUNCREW_STORAGE['sc_custom'] = '';
	
	var sc_name = RUNCREW_STORAGE['shortcodes_current_idx'];
	var sc = RUNCREW_SHORTCODES_DATA[sc_name];
	var tabs = popup.find('.runcrew_shortcodes_tab > ul > li');
	var decor = !runcrew_isset(sc.decorate) || sc.decorate;
	var rez = '[' + sc_name + runcrew_shortcodes_get_code_from_tab(popup.find('#runcrew_shortcodes_tab_0_content').eq(0)) + ']'
			// + (decor ? '\n' : '')
			;
	if (runcrew_isset(sc.children)) {
		if (RUNCREW_STORAGE['sc_custom']!='no') {
			var decor2 = !runcrew_isset(sc.children.decorate) || sc.children.decorate;
			for (var i=0; i<tabs.length; i++) {
				var tab = tabs.eq(i);
				var idx = tab.data('id');
				if (isNaN(idx) || parseInt(idx) < 1) continue;
				var content = popup.find('#runcrew_shortcodes_tab_' + idx + '_content').eq(0);
				rez += (decor2 ? '\n\t' : '') + '[' + sc.children.name + runcrew_shortcodes_get_code_from_tab(content) + ']';	// + (decor2 ? '\n' : '');
				if (runcrew_isset(sc.children.container) && sc.children.container) {
					if (content.find('[data-param="_content_"]').length > 0) {
						rez += 
							//(decor2 ? '\t\t' : '') + 
							content.find('[data-param="_content_"]').val()
							// + (decor2 ? '\n' : '')
							;
					}
					rez += 
						//(decor2 ? '\t' : '') + 
						'[/' + sc.children.name + ']'
						// + (decor ? '\n' : '')
						;
				}
			}
		}
	} else if (runcrew_isset(sc.container) && sc.container && popup.find('#runcrew_shortcodes_tab_0_content [data-param="_content_"]').length > 0) {
		rez += 
			//(decor ? '\t' : '') + 
			popup.find('#runcrew_shortcodes_tab_0_content [data-param="_content_"]').val()
			// + (decor ? '\n' : '')
			;
	}
	if (runcrew_isset(sc.container) && sc.container || runcrew_isset(sc.children))
		rez += 
			(runcrew_isset(sc.children) && decor && RUNCREW_STORAGE['sc_custom']!='no' ? '\n' : '')
			+ '[/' + sc_name + ']'
			 //+ (decor ? '\n' : '')
			 ;
	return rez;
}

// Collect all parameters from tab into string
function runcrew_shortcodes_get_code_from_tab(tab) {
	var rez = ''
	var mainTab = tab.attr('id').indexOf('tab_0') > 0;
	tab.find('[data-param]').each(function () {
		var field = jQuery(this);
		var param = field.data('param');
		if (!field.parents('.runcrew_options_field').hasClass('runcrew_options_no_use') && param.substr(0, 1)!='_' && !runcrew_empty(field.val()) && field.val()!='none' && (field.attr('type') != 'checkbox' || field.get(0).checked)) {
			rez += ' '+param+'="'+runcrew_shortcodes_prepare_value(field.val())+'"';
		}
		// On main tab detect param "custom"
		if (mainTab && param=='custom') {
			RUNCREW_STORAGE['sc_custom'] = field.val();
		}
	});
	// Get additional params for general tab from items tabs
	if (RUNCREW_STORAGE['sc_custom']!='no' && mainTab) {
		var sc = RUNCREW_SHORTCODES_DATA[RUNCREW_STORAGE['shortcodes_current_idx']];
		var sc_name = RUNCREW_STORAGE['shortcodes_current_idx'];
		if (sc_name == 'trx_columns' || sc_name == 'trx_skills' || sc_name == 'trx_team' || sc_name == 'trx_price_table') {	// Determine "count" parameter
			var cnt = 0;
			tab.siblings('div').each(function() {
				var item_tab = jQuery(this);
				var merge = parseInt(item_tab.find('[data-param="span"]').val());
				cnt += !isNaN(merge) && merge > 0 ? merge : 1;
			});
			rez += ' count="'+cnt+'"';
		}
	}
	return rez;
}


// Shortcode parameters builder
//-------------------------------------------------------------------------------------------

// Prepare layout from shortcode object (array)
function runcrew_shortcodes_prepare_layout(field) {
	"use strict";
	// Make params cloneable
	field['params'] = [field['params']];
	if (!runcrew_empty(field.children)) {
		field.children['params'] = [field.children['params']];
	}
	// Prepare output
	var output = '<div class="runcrew_shortcodes_body runcrew_options_body"><form>';
	output += runcrew_shortcodes_show_tabs(field);
	output += runcrew_shortcodes_show_field(field, 0);
	if (!runcrew_empty(field.children)) {
		RUNCREW_STORAGE['shortcodes_tab_clone_content'] = runcrew_shortcodes_show_field(field.children, 1);
		output += RUNCREW_STORAGE['shortcodes_tab_clone_content'];
	}
	output += '</div></form></div>';
	return output;
}



// Show tabs
function runcrew_shortcodes_show_tabs(field) {
	"use strict";
	// html output
	var output = '<div class="runcrew_shortcodes_tab runcrew_options_container runcrew_options_tab">'
		+ '<ul>'
		+ RUNCREW_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, 0).replace('{icon}', 'cog').replace('{title}', 'General');
	if (runcrew_isset(field.children)) {
		for (var i=0; i<field.children.params.length; i++)
			output += RUNCREW_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, i+1).replace('{icon}', 'cancel').replace('{title}', field.children.title + ' ' + (i+1));
		output += RUNCREW_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, 'add').replace('{icon}', 'list-add').replace('{title}', '');
	}
	output += '</ul>';
	return output;
}

// Add new tab
function runcrew_shortcodes_add_tab(tab) {
	"use strict";
	var idx = 0;
	tab.siblings().each(function () {
		"use strict";
		var i = parseInt(jQuery(this).data('id'));
		if (i > idx) idx = i;
	});
	idx++;
	tab.before( RUNCREW_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, idx).replace('{icon}', 'cancel').replace('{title}', RUNCREW_SHORTCODES_DATA[RUNCREW_STORAGE['shortcodes_current_idx']].children.title + ' ' + idx) );
	tab.parents('.runcrew_shortcodes_tab').append(RUNCREW_STORAGE['shortcodes_tab_clone_content'].replace(/tab_1_/g, 'tab_' + idx + '_'));
	tab.parents('.runcrew_shortcodes_tab').tabs('refresh');
	runcrew_options_init(tab.parents('.runcrew_shortcodes_tab').find('.runcrew_options_tab_content').eq(idx));
	tab.prev().find('a').trigger('click');
}



// Show one field layout
function runcrew_shortcodes_show_field(field, tab_idx) {
	"use strict";
	
	// html output
	var output = '';

	// Parse field params
	for (var clone_num in field['params']) {
		var tab_id = 'tab_' + (parseInt(tab_idx) + parseInt(clone_num));
		output += '<div id="runcrew_shortcodes_' + tab_id + '_content" class="runcrew_options_content runcrew_options_tab_content">';

		for (var param_num in field['params'][clone_num]) {
			
			var param = field['params'][clone_num][param_num];
			var id = tab_id + '_' + param_num;
	
			// Divider after field
			var divider = runcrew_isset(param['divider']) && param['divider'] ? ' runcrew_options_divider' : '';
		
			// Setup default parameters
			if (param['type']=='media') {
				if (!runcrew_isset(param['before'])) param['before'] = {};
				param['before'] = runcrew_merge_objects({
						'title': 'Choose image',
						'action': 'media_upload',
						'type': 'image',
						'multiple': false,
						'sizes': false,
						'linked_field': '',
						'captions': { 	
							'choose': 'Choose image',
							'update': 'Select image'
							}
					}, param['before']);
				if (!runcrew_isset(param['after'])) param['after'] = {};
				param['after'] = runcrew_merge_objects({
						'icon': 'iconadmin-cancel',
						'action': 'media_reset'
					}, param['after']);
			}
			if (param['type']=='color' && (RUNCREW_STORAGE['shortcodes_cp']=='tiny' || (runcrew_isset(param['style']) && param['style']!='wp'))) {
				if (!runcrew_isset(param['after'])) param['after'] = {};
				param['after'] = runcrew_merge_objects({
						'icon': 'iconadmin-cancel',
						'action': 'color_reset'
					}, param['after']);
			}
		
			// Buttons before and after field
			var before = '', after = '', buttons_classes = '', rez, rez2, i, key, opt;
			
			if (runcrew_isset(param['before'])) {
				rez = runcrew_shortcodes_action_button(param['before'], 'before');
				before = rez[0];
				buttons_classes += rez[1];
			}
			if (runcrew_isset(param['after'])) {
				rez = runcrew_shortcodes_action_button(param['after'], 'after');
				after = rez[0];
				buttons_classes += rez[1];
			}
			if (runcrew_in_array(param['type'], ['list', 'select', 'fonts']) || (param['type']=='socials' && (runcrew_empty(param['style']) || param['style']=='icons'))) {
				buttons_classes += ' runcrew_options_button_after_small';
			}

			if (param['type'] != 'hidden') {
				output += '<div class="runcrew_options_field'
					+ ' runcrew_options_field_' + (runcrew_in_array(param['type'], ['list','fonts']) ? 'select' : param['type'])
					+ (runcrew_in_array(param['type'], ['media', 'fonts', 'list', 'select', 'socials', 'date', 'time']) ? ' runcrew_options_field_text'  : '')
					+ (param['type']=='socials' && !runcrew_empty(param['style']) && param['style']=='images' ? ' runcrew_options_field_images'  : '')
					+ (param['type']=='socials' && (runcrew_empty(param['style']) || param['style']=='icons') ? ' runcrew_options_field_icons'  : '')
					+ (runcrew_isset(param['dir']) && param['dir']=='vertical' ? ' runcrew_options_vertical' : '')
					+ (!runcrew_empty(param['multiple']) ? ' runcrew_options_multiple' : '')
					+ (runcrew_isset(param['size']) ? ' runcrew_options_size_'+param['size'] : '')
					+ (runcrew_isset(param['class']) ? ' ' + param['class'] : '')
					+ divider 
					+ '">' 
					+ "\n"
					+ '<label class="runcrew_options_field_label" for="' + id + '">' + param['title']
					+ '</label>'
					+ "\n"
					+ '<div class="runcrew_options_field_content'
					+ buttons_classes
					+ '">'
					+ "\n";
			}
			
			if (!runcrew_isset(param['value'])) {
				param['value'] = '';
			}
			

			switch ( param['type'] ) {
	
			case 'hidden':
				output += '<input class="runcrew_options_input runcrew_options_input_hidden" name="' + id + '" id="' + id + '" type="hidden" value="' + runcrew_shortcodes_prepare_value(param['value']) + '" data-param="' + runcrew_shortcodes_prepare_value(param_num) + '" />';
			break;

			case 'date':
				if (runcrew_isset(param['style']) && param['style']=='inline') {
					output += '<div class="runcrew_options_input_date"'
						+ ' id="' + id + '_calendar"'
						+ ' data-format="' + (!runcrew_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!runcrew_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-linked-field="' + (!runcrew_empty(data['linked_field']) ? data['linked_field'] : id) + '"'
						+ '></div>'
						+ '<input id="' + id + '"'
							+ ' name="' + id + '"'
							+ ' type="hidden"'
							+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
							+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
							+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
							+ ' />';
				} else {
					output += '<input class="runcrew_options_input runcrew_options_input_date' + (!runcrew_empty(param['mask']) ? ' runcrew_options_input_masked' : '') + '"'
						+ ' name="' + id + '"'
						+ ' id="' + id + '"'
						+ ' type="text"'
						+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-format="' + (!runcrew_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!runcrew_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
						+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
						+ before 
						+ after;
				}
			break;

			case 'text':
				output += '<input class="runcrew_options_input runcrew_options_input_text' + (!runcrew_empty(param['mask']) ? ' runcrew_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
					+ (!runcrew_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
					+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
				+ before 
				+ after;
			break;
		
			case 'textarea':
				var cols = runcrew_isset(param['cols']) && param['cols'] > 10 ? param['cols'] : '40';
				var rows = runcrew_isset(param['rows']) && param['rows'] > 1 ? param['rows'] : '8';
				output += '<textarea class="runcrew_options_input runcrew_options_input_textarea"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' cols="' + cols + '"'
					+ ' rows="' + rows + '"'
					+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
					+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
					+ '>'
					+ param['value']
					+ '</textarea>';
			break;

			case 'spinner':
				output += '<input class="runcrew_options_input runcrew_options_input_spinner' + (!runcrew_empty(param['mask']) ? ' runcrew_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"' 
					+ (!runcrew_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ (runcrew_isset(param['min']) ? ' data-min="'+param['min']+'"' : '') 
					+ (runcrew_isset(param['max']) ? ' data-max="'+param['max']+'"' : '') 
					+ (!runcrew_empty(param['step']) ? ' data-step="'+param['step']+'"' : '') 
					+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
					+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />' 
					+ '<span class="runcrew_options_arrows"><span class="runcrew_options_arrow_up iconadmin-up-dir"></span><span class="runcrew_options_arrow_down iconadmin-down-dir"></span></span>';
			break;

			case 'tags':
				var tags = param['value'].split(RUNCREW_STORAGE['shortcodes_delimiter']);
				if (tags.length > 0) {
					for (i=0; i<tags.length; i++) {
						if (runcrew_empty(tags[i])) continue;
						output += '<span class="runcrew_options_tag iconadmin-cancel">' + tags[i] + '</span>';
					}
				}
				output += '<input class="runcrew_options_input_tags"'
					+ ' type="text"'
					+ ' value=""'
					+ ' />'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
						+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case "checkbox": 
				output += '<input type="checkbox" class="runcrew_options_input runcrew_options_input_checkbox"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' value="true"' 
					+ (param['value'] == 'true' ? ' checked="checked"' : '') 
					+ (!runcrew_empty(param['disabled']) ? ' readonly="readonly"' : '') 
					+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
					+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<label for="' + id + '" class="' + (!runcrew_empty(param['disabled']) ? 'runcrew_options_state_disabled' : '') + (param['value']=='true' ? ' runcrew_options_state_checked' : '') + '"><span class="runcrew_options_input_checkbox_image iconadmin-check"></span>' + (!runcrew_empty(param['label']) ? param['label'] : param['title']) + '</label>';
			break;
		
			case "radio":
				for (key in param['options']) { 
					output += '<span class="runcrew_options_radioitem"><input class="runcrew_options_input runcrew_options_input_radio" type="radio"'
						+ ' name="' + id + '"'
						+ ' value="' + runcrew_shortcodes_prepare_value(key) + '"'
						+ ' data-value="' + runcrew_shortcodes_prepare_value(key) + '"'
						+ (param['value'] == key ? ' checked="checked"' : '') 
						+ ' id="' + id + '_' + key + '"'
						+ ' />'
						+ '<label for="' + id + '_' + key + '"' + (param['value'] == key ? ' class="runcrew_options_state_checked"' : '') + '><span class="runcrew_options_input_radio_image iconadmin-circle-empty' + (param['value'] == key ? ' iconadmin-dot-circled' : '') + '"></span>' + param['options'][key] + '</label></span>';
				}
				output += '<input type="hidden"'
						+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
						+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';

			break;
		
			case "switch":
				opt = [];
				i = 0;
				for (key in param['options']) {
					opt[i++] = {'key': key, 'title': param['options'][key]};
					if (i==2) break;
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + runcrew_shortcodes_prepare_value(runcrew_empty(param['value']) ? opt[0]['key'] : param['value']) + '"'
					+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
					+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<span class="runcrew_options_switch' + (param['value']==opt[1]['key'] ? ' runcrew_options_state_off' : '') + '"><span class="runcrew_options_switch_inner iconadmin-circle"><span class="runcrew_options_switch_val1" data-value="' + opt[0]['key'] + '">' + opt[0]['title'] + '</span><span class="runcrew_options_switch_val2" data-value="' + opt[1]['key'] + '">' + opt[1]['title'] + '</span></span></span>';
			break;

			case 'media':
				output += '<input class="runcrew_options_input runcrew_options_input_text runcrew_options_input_media"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
					+ (!runcrew_isset(param['readonly']) || param['readonly'] ? ' readonly="readonly"' : '')
					+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
					+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before 
					+ after;
				if (!runcrew_empty(param['value'])) {
					var fname = runcrew_get_file_name(param['value']);
					var fext  = runcrew_get_file_ext(param['value']);
					output += '<a class="runcrew_options_image_preview" rel="prettyPhoto" target="_blank" href="' + param['value'] + '">' + (fext!='' && runcrew_in_list('jpg,png,gif', fext, ',') ? '<img src="'+param['value']+'" alt="" />' : '<span>'+fname+'</span>') + '</a>';
				}
			break;
		
			case 'button':
				rez = runcrew_shortcodes_action_button(param, 'button');
				output += rez[0];
			break;

			case 'range':
				output += '<div class="runcrew_options_input_range" data-step="'+(!runcrew_empty(param['step']) ? param['step'] : 1) + '">'
					+ '<span class="runcrew_options_range_scale"><span class="runcrew_options_range_scale_filled"></span></span>';
				if (param['value'].toString().indexOf(RUNCREW_STORAGE['shortcodes_delimiter']) == -1)
					param['value'] = Math.min(param['max'], Math.max(param['min'], param['value']));
				var sliders = param['value'].toString().split(RUNCREW_STORAGE['shortcodes_delimiter']);
				for (i=0; i<sliders.length; i++) {
					output += '<span class="runcrew_options_range_slider"><span class="runcrew_options_range_slider_value">' + sliders[i] + '</span><span class="runcrew_options_range_slider_button"></span></span>';
				}
				output += '<span class="runcrew_options_range_min">' + param['min'] + '</span><span class="runcrew_options_range_max">' + param['max'] + '</span>'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
						+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
					+ '</div>';			
			break;
		
			case "checklist":
				for (key in param['options']) { 
					output += '<span class="runcrew_options_listitem'
						+ (runcrew_in_list(param['value'], key, RUNCREW_STORAGE['shortcodes_delimiter']) ? ' runcrew_options_state_checked' : '') + '"'
						+ ' data-value="' + runcrew_shortcodes_prepare_value(key) + '"'
						+ '>'
						+ param['options'][key]
						+ '</span>';
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
					+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
					+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />';
			break;
		
			case 'fonts':
				for (key in param['options']) {
					param['options'][key] = key;
				}
			case 'list':
			case 'select':
				if (!runcrew_isset(param['options']) && !runcrew_empty(param['from']) && !runcrew_empty(param['to'])) {
					param['options'] = [];
					for (i = param['from']; i <= param['to']; i+=(!runcrew_empty(param['step']) ? param['step'] : 1)) {
						param['options'][i] = i;
					}
				}
				rez = runcrew_shortcodes_menu_list(param);
				if (runcrew_empty(param['style']) || param['style']=='select') {
					output += '<input class="runcrew_options_input runcrew_options_input_select" type="text" value="' + runcrew_shortcodes_prepare_value(rez[1]) + '"'
						+ ' readonly="readonly"'
						//+ (!runcrew_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
						+ ' />'
						+ '<span class="runcrew_options_field_after runcrew_options_with_action iconadmin-down-open" onchange="runcrew_options_action_show_menu(this);return false;"></span>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
						+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'images':
				rez = runcrew_shortcodes_menu_list(param);
				if (runcrew_empty(param['style']) || param['style']=='select') {
					output += '<div class="runcrew_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
						+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case 'icons':
				rez = runcrew_shortcodes_menu_list(param);
				if (runcrew_empty(param['style']) || param['style']=='select') {
					output += '<div class="runcrew_options_caption_icon iconadmin-down-open"><span class="' + rez[1] + '"></span></div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
						+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'socials':
				if (!runcrew_is_object(param['value'])) param['value'] = {'url': '', 'icon': ''};
				rez = runcrew_shortcodes_menu_list(param);
				if (runcrew_empty(param['style']) || param['style']=='icons') {
					rez2 = runcrew_shortcodes_action_button({
						'action': runcrew_empty(param['style']) || param['style']=='icons' ? 'select_icon' : '',
						'icon': (runcrew_empty(param['style']) || param['style']=='icons') && !runcrew_empty(param['value']['icon']) ? param['value']['icon'] : 'iconadmin-users'
						}, 'after');
				} else
					rez2 = ['', ''];
				output += '<input class="runcrew_options_input runcrew_options_input_text runcrew_options_input_socials' 
					+ (!runcrew_empty(param['mask']) ? ' runcrew_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text" value="' + runcrew_shortcodes_prepare_value(param['value']['url']) + '"' 
					+ (!runcrew_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
					+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ rez2[0];
				if (!runcrew_empty(param['style']) && param['style']=='images') {
					output += '<div class="runcrew_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '_icon' + '" type="hidden" value="' + runcrew_shortcodes_prepare_value(param['value']['icon']) + '" />';
			break;

			case "color":
				var cp_style = runcrew_isset(param['style']) ? param['style'] : RUNCREW_STORAGE['shortcodes_cp'];
				output += '<input class="runcrew_options_input runcrew_options_input_color runcrew_options_input_color_'+cp_style +'"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' data-param="' + runcrew_shortcodes_prepare_value(param_num) + '"'
					+ ' type="text"'
					+ ' value="' + runcrew_shortcodes_prepare_value(param['value']) + '"'
					+ (!runcrew_empty(param['action']) ? ' onchange="runcrew_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before;
				if (cp_style=='custom')
					output += '<span class="runcrew_options_input_colorpicker iColorPicker"></span>';
				else if (cp_style=='tiny')
					output += after;
			break;   
	
			}

			if (param['type'] != 'hidden') {
				output += '</div>';
				if (!runcrew_empty(param['desc']))
					output += '<div class="runcrew_options_desc">' + param['desc'] + '</div>' + "\n";
				output += '</div>' + "\n";
			}

		}

		output += '</div>';
	}

	
	return output;
}



// Return menu items list (menu, images or icons)
function runcrew_shortcodes_menu_list(field) {
	"use strict";
	if (field['type'] == 'socials') field['value'] = field['value']['icon'];
	var list = '<div class="runcrew_options_input_menu ' + (runcrew_empty(field['style']) ? '' : ' runcrew_options_input_menu_' + field['style']) + '">';
	var caption = '';
	for (var key in field['options']) {
		var value = field['options'][key];
		if (runcrew_in_array(field['type'], ['list', 'icons', 'socials'])) key = value;
		var selected = '';
		if (runcrew_in_list(field['value'], key, RUNCREW_STORAGE['shortcodes_delimiter'])) {
			caption = value;
			selected = ' runcrew_options_state_checked';
		}
		list += '<span class="runcrew_options_menuitem' 
			+ selected 
			+ '" data-value="' + runcrew_shortcodes_prepare_value(key) + '"'
			+ '>';
		if (runcrew_in_array(field['type'], ['list', 'select', 'fonts']))
			list += value;
		else if (field['type'] == 'icons' || (field['type'] == 'socials' && field['style'] == 'icons'))
			list += '<span class="' + value + '"></span>';
		else if (field['type'] == 'images' || (field['type'] == 'socials' && field['style'] == 'images'))
			//list += '<img src="' + value + '" data-icon="' + key + '" alt="" class="runcrew_options_input_image" />';
			list += '<span style="background-image:url(' + value + ')" data-src="' + value + '" data-icon="' + key + '" class="runcrew_options_input_image"></span>';
		list += '</span>';
	}
	list += '</div>';
	return [list, caption];
}



// Return action button
function runcrew_shortcodes_action_button(data, type) {
	"use strict";
	var class_name = ' runcrew_options_button_' + type + (runcrew_empty(data['title']) ? ' runcrew_options_button_'+type+'_small' : '');
	var output = '<span class="' 
				+ (type == 'button' ? 'runcrew_options_input_button'  : 'runcrew_options_field_'+type)
				+ (!runcrew_empty(data['action']) ? ' runcrew_options_with_action' : '')
				+ (!runcrew_empty(data['icon']) ? ' '+data['icon'] : '')
				+ '"'
				+ (!runcrew_empty(data['icon']) && !runcrew_empty(data['title']) ? ' title="'+runcrew_shortcodes_prepare_value(data['title'])+'"' : '')
				+ (!runcrew_empty(data['action']) ? ' onclick="runcrew_options_action_'+data['action']+'(this);return false;"' : '')
				+ (!runcrew_empty(data['type']) ? ' data-type="'+data['type']+'"' : '')
				+ (!runcrew_empty(data['multiple']) ? ' data-multiple="'+data['multiple']+'"' : '')
				+ (!runcrew_empty(data['sizes']) ? ' data-sizes="'+data['sizes']+'"' : '')
				+ (!runcrew_empty(data['linked_field']) ? ' data-linked-field="'+data['linked_field']+'"' : '')
				+ (!runcrew_empty(data['captions']) && !runcrew_empty(data['captions']['choose']) ? ' data-caption-choose="'+runcrew_shortcodes_prepare_value(data['captions']['choose'])+'"' : '')
				+ (!runcrew_empty(data['captions']) && !runcrew_empty(data['captions']['update']) ? ' data-caption-update="'+runcrew_shortcodes_prepare_value(data['captions']['update'])+'"' : '')
				+ '>'
				+ (type == 'button' || (runcrew_empty(data['icon']) && !runcrew_empty(data['title'])) ? data['title'] : '')
				+ '</span>';
	return [output, class_name];
}

// Prepare string to insert as parameter's value
function runcrew_shortcodes_prepare_value(val) {
	return typeof val == 'string' ? val.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;') : val;
}
