<?php

function testimonialswidget_admin_menu() 
{
	global $testimonialswidget_admin_userlevel;
	add_object_page('Testimonials Widget', 'Testimonials', $testimonialswidget_admin_userlevel, 'testimonials-widget', 'testimonialswidget_testimonials_management');
}
add_action('admin_menu', 'testimonialswidget_admin_menu');


function testimonialswidget_count($condition = "")
{
	global $wpdb;
	$sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "testimonialswidget ".$condition;
	$count = $wpdb->get_var($sql);
	return $count;
}


function testimonialswidget_pagenav($total, $current = 1, $format = 0, $paged = 'paged', $url = "")
{
	if($total == 1 && $current == 1) return "";
	
	if(!$url) {
		$url = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$url .= "s";}
		$url .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["PHP_SELF"];
		} else {
			$url .= $_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
		}
		if($query_string = $_SERVER['QUERY_STRING']) {
			$parms = explode('&', $query_string);
			$y = '?';
			foreach($parms as $parm) {
				$x = explode('=', $parm);
				if($x[0] == $paged) {
					$query_string = str_replace($y.$parm, '', $query_string);
				}
				else $y = '&';
			}
			if($query_string) {
				$url .= '?'.$query_string;
				$a = '&';
			}
			else $a = '?';	
		}
		else $a = '?';
	}
	else {
		$a = '?';
		if(strpos($url, '?')) $a = '&';	
	}
	
	if(!$format || $format > 2 || $format < 0 || !is_numeric($format)) {	
		if($total <= 8) $format = 1;
		else $format = 2;
	}
	
	
	if($current > $total) $current = $total;
		$pagenav = "";

	if($format == 2) {
		$first_disabled = $prev_disabled = $next_disabled = $last_disabled = '';
		if($current == 1)
			$first_disabled = $prev_disabled = ' disabled';
		if($current == $total)
			$next_disabled = $last_disabled = ' disabled';

		$pagenav .= "<a class=\"first-page{$first_disabled}\" title=\"".__('Go to the first page', 'testimonials-widget')."\" href=\"{$url}\">&laquo;</a>&nbsp;&nbsp;";
		$pagenav .= "<a class=\"prev-page{$prev_disabled}\" title=\"".__('Go to the previous page', 'testimonials-widget')."\" href=\"{$url}{$a}{$paged}=".($current - 1)."\">&#139;</a>&nbsp;&nbsp;";
		$pagenav .= '<span class="paging-input">'.$current.' of <span class="total-pages">'.$total.'</span></span>';
		$pagenav .= "&nbsp;&nbsp;<a class=\"next-page{$next_disabled}\" title=\"".__('Go to the next page', 'testimonials-widget')."\" href=\"{$url}{$a}{$paged}=".($current + 1)."\">&#155;</a>";
		$pagenav .= "&nbsp;&nbsp;<a class=\"last-page{$last_disabled}\" title=\"".__('Go to the last page', 'testimonials-widget')."\" href=\"{$url}{$a}{$paged}={$total}\">&raquo;</a>";
	
	}
	else {
		$pagenav = __("Goto page:", 'testimonials-widget');
		for( $i = 1; $i <= $total; $i++ ) {
			if($i == $current)
				$pagenav .= "&nbsp<strong>{$i}</strong>";
			else if($i == 1)
				$pagenav .= "&nbsp;<a href=\"{$url}\">{$i}</a>";
			else 
				$pagenav .= "&nbsp;<a href=\"{$url}{$a}{$paged}={$i}\">{$i}</a>";
		}
	}
	return $pagenav;
}


function testimonialswidget_addtestimonial($testimonial, $author = "", $source = "", $tags = "", $public = 'yes')
{
	if(!$testimonial) return __('Nothing added to the database.', 'testimonials-widget');
	global $wpdb;
	$table_name = $wpdb->prefix . "testimonialswidget";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
		return __('Database table not found', 'testimonials-widget');
	else //Add the testimonial data to the database
	{
		
		$testimonial = stripslashes($testimonial);
		$author = stripslashes($author);	
		$source = stripslashes($source);	
		$tags = stripslashes($tags);

		$testimonial = "'".$wpdb->escape($testimonial)."'";
		$author = $author?"'".$wpdb->escape($author)."'":"NULL";
		$source = $source?"'".$wpdb->escape($source)."'":"NULL";
		$tags = explode(',', $tags);
		foreach ($tags as $key => $tag)
			$tags[$key] = trim($tag);
		$tags = implode(',', $tags);
		$tags = $tags?"'".$wpdb->escape($tags)."'":"NULL";
		if(!$public) $public = "'no'";
		else $public = "'yes'";
		$insert = "INSERT INTO " . $table_name .
			"(testimonial, author, source, tags, public, time_added)" .
			"VALUES ({$testimonial}, {$author}, {$source}, {$tags}, {$public}, NOW())";
		$results = $wpdb->query( $insert );
		if(FALSE === $results)
			return __('There was an error in the MySQL query', 'testimonials-widget');
		else
			return __('Testimonial added', 'testimonials-widget');
   }
}

function testimonialswidget_edittestimonial($testimonial_id, $testimonial, $author = "", $source = "", $tags = "", $public = 'yes')
{
	if(!$testimonial) return __('Testimonial not updated.', 'testimonials-widget');
	if(!$testimonial_id) return srgq_addtestimonial($testimonial, $author, $source, $public);
	global $wpdb;
	$table_name = $wpdb->prefix . "testimonialswidget";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
		return __('Database table not found', 'testimonials-widget');
	else //Update database
	{
		
		$testimonial = stripslashes($testimonial);
		$author = stripslashes($author);	
		$source = stripslashes($source);	
		$tags = stripslashes($tags);

	  	$testimonial = "'".$wpdb->escape($testimonial)."'";
		$author = $author?"'".$wpdb->escape($author)."'":"NULL";
		$source = $source?"'".$wpdb->escape($source)."'":"NULL";
		$tags = explode(',', $tags);
		foreach ($tags as $key => $tag)
			$tags[$key] = trim($tag);
		$tags = implode(',', $tags);
		$tags = $tags?"'".$wpdb->escape($tags)."'":"NULL";
		if(!$public) $public = "'no'";
		else $public = "'yes'";
		$update = "UPDATE " . $table_name . "
			SET testimonial = {$testimonial},
				author = {$author},
				source = {$source}, 
				tags = {$tags},
				public = {$public}, 
				time_updated = NOW()
			WHERE testimonial_id = $testimonial_id";
		$results = $wpdb->query( $update );
		if(FALSE === $results)
			return __('There was an error in the MySQL query', 'testimonials-widget');		
		else
			return __('Changes saved', 'testimonials-widget');
   }
}


function testimonialswidget_deletetestimonial($testimonial_id)
{
	if($testimonial_id) {
		global $wpdb;
		$sql = "DELETE from " . $wpdb->prefix ."testimonialswidget" .
			" WHERE testimonial_id = " . $testimonial_id;
		if(FALSE === $wpdb->query($sql))
			return __('There was an error in the MySQL query', 'testimonials-widget');		
		else
			return __('Testimonial deleted', 'testimonials-widget');
	}
	else return __('The testimonial cannot be deleted', 'testimonials-widget');
}

function testimonialswidget_gettestimonialdata($testimonial_id)
{
	global $wpdb;
	$sql = "SELECT testimonial_id, testimonial, author, source, tags, public
		FROM " . $wpdb->prefix . "testimonialswidget 
		WHERE testimonial_id = {$testimonial_id}";
	$testimonial_data = $wpdb->get_row($sql, ARRAY_A);	
	return $testimonial_data;
}

function testimonialswidget_editform($testimonial_id = 0)
{
	$public_selected = " checked=\"checked\"";
	$submit_value = __('Add Testimonial', 'testimonials-widget');
	$form_name = "addtestimonial";
	$action_url = get_bloginfo('wpurl')."/wp-admin/admin.php?page=testimonials-widget#addnew";
	$testimonial = $author = $source = $tags = $hidden_input = $back = "";

	if($testimonial_id) {
		$form_name = "edittestimonial";
		$testimonial_data = testimonialswidget_gettestimonialdata($testimonial_id);
		foreach($testimonial_data as $key => $value)
			$testimonial_data[$key] = $testimonial_data[$key];
		extract($testimonial_data);
		$testimonial = htmlspecialchars($testimonial);
		$author = htmlspecialchars($author);
		$source = htmlspecialchars($source);
		$tags = implode(', ', explode(',', $tags));
		$hidden_input = "<input type=\"hidden\" name=\"testimonial_id\" value=\"{$testimonial_id}\" />";
		if($public == 'no') $public_selected = "";
		$submit_value = __('Save changes', 'testimonials-widget');
		$back = "<input type=\"submit\" name=\"submit\" value=\"".__('Back', 'testimonials-widget')."\" />&nbsp;";
		$action_url = get_bloginfo('wpurl')."/wp-admin/admin.php?page=testimonials-widget";
	}

	$testimonial_label = __('The testimonial', 'testimonials-widget');
	$author_label = __('Author', 'testimonials-widget');
	$source_label = __('Source', 'testimonials-widget');
	$tags_label = __('Tags', 'testimonials-widget');
	$public_label = __('Public?', 'testimonials-widget');
	$optional_text = __('optional', 'testimonials-widget');
	$comma_separated_text = __('comma separated', 'testimonials-widget');
	

	$display =<<< EDITFORM
<form name="{$form_name}" method="post" action="{$action_url}">
	{$hidden_input}
	<table class="form-table" cellpadding="5" cellspacing="2" width="100%">
		<tbody><tr class="form-field form-required">
			<th style="text-align:left;" scope="row" valign="top"><label for="testimonialswidget_testimonial">{$testimonial_label}</label></th>
			<td><textarea id="testimonialswidget_testimonial" name="testimonial" rows="5" cols="50" style="width: 97%;">{$testimonial}</textarea></td>
		</tr>
		<tr class="form-field">
			<th style="text-align:left;" scope="row" valign="top"><label for="testimonialswidget_author">{$author_label}</label></th>
			<td><input type="text" id="testimonialswidget_author" name="author" size="40" value="{$author}" /><br />{$optional_text}</td>
		</tr>
		<tr class="form-field">
			<th style="text-align:left;" scope="row" valign="top"><label for="testimonialswidget_source">{$source_label}</label></th>
			<td><input type="text" id="testimonialswidget_source" name="source" size="40" value="{$source}" /><br />{$optional_text}</td>
		</tr>
		<tr class="form-field">
			<th style="text-align:left;" scope="row" valign="top"><label for="testimonialswidget_tags">{$tags_label}</label></th>
			<td><input type="text" id="testimonialswidget_tags" name="tags" size="40" value="{$tags}" /><br />{$optional_text}, {$comma_separated_text}</small></td>
		</tr>
		<tr>
			<th style="text-align:left;" scope="row" valign="top"><label for="testimonialswidget_public">{$public_label}</label></th>
			<td><input type="checkbox" id="testimonialswidget_public" name="public"{$public_selected} />
		</tr></tbody>
	</table>
	<p class="submit">{$back}<input name="submit" value="{$submit_value}" type="submit" class="button button-primary" /></p>
</form>
EDITFORM;
	return $display;
}

function testimonialswidget_changevisibility($testimonial_ids, $public = 'yes')
{
	if(!$testimonial_ids)
		return __('Nothing done!', 'testimonials-widget');
	global $wpdb;
	$sql = "UPDATE ".$wpdb->prefix."testimonialswidget 
		SET public = '".$public."',
			time_updated = NOW()
		WHERE testimonial_id IN (".implode(', ', $testimonial_ids).")";
	$wpdb->query($sql);
	if($public == 'yes')
		return __("Selected testimonials made public", 'testimonials-widget');
	else
		return __("Selected testimonials made private", 'testimonials-widget');
}

function testimonialswidget_bulkdelete($testimonial_ids)
{
	if(!$testimonial_ids)
		return __('Nothing done!', 'testimonials-widget');
	global $wpdb;
	$sql = "DELETE FROM ".$wpdb->prefix."testimonialswidget 
		WHERE testimonial_id IN (".implode(', ', $testimonial_ids).")";
	$wpdb->query($sql);
	return __('Testimonial(s) deleted', 'testimonials-widget');
}



function testimonialswidget_testimonials_management()
{	

	global $testimonialswidget_db_version;
	$options = get_option('testimonialswidget');
	$display = $msg = $testimonials_list = $alternate = "";
	
	if($options['db_version'] != $testimonialswidget_db_version )
		testimonialswidget_install();
		
	if(isset($_REQUEST['submit'])) {
		if($_REQUEST['submit'] == __('Add Testimonial', 'testimonials-widget')) {
			extract($_REQUEST);
			$msg = testimonialswidget_addtestimonial($testimonial, $author, $source, $tags, $public);
		}
		else if($_REQUEST['submit'] == __('Save changes', 'testimonials-widget')) {
			extract($_REQUEST);
			$msg = testimonialswidget_edittestimonial($testimonial_id, $testimonial, $author, $source, $tags, $public);
		}
	}
	else if(isset($_REQUEST['action'])) {
		if($_REQUEST['action'] == 'edittestimonial') {
			$display .= "<div class=\"wrap\">\n<h2>Testimonials Widget &raquo; ".__('Edit testimonial', 'testimonials-widget')."</h2>";
			$display .=  testimonialswidget_editform($_REQUEST['id']);
			$display .= "</div>";
			echo $display;
			return;
		}
		else if($_REQUEST['action'] == 'deltestimonial') {
			$msg = testimonialswidget_deletetestimonial($_REQUEST['id']);
		}
	}
	else if(isset($_REQUEST['bulkactionsubmit']))  {
		if($_REQUEST['bulkaction'] == 'delete') 
			$msg = testimonialswidget_bulkdelete($_REQUEST['bulkcheck']);
		if($_REQUEST['bulkaction'] == 'make_public') {
			$msg = testimonialswidget_changevisibility($_REQUEST['bulkcheck'], 'yes');
		}
		if($_REQUEST['bulkaction'] == 'keep_private') {
			$msg = testimonialswidget_changevisibility($_REQUEST['bulkcheck'], 'no');
		}
	}
	
	
	$display .= "<div class=\"wrap\">";
	
	if($msg)
		$display .= "<div id=\"message\" class=\"updated fade\"><p>{$msg}</p></div>";

	$display .= "<h2>Testimonials Widget <a href=\"#addnew\" class=\"add-new-h2\">".__('Add new testimonial', 'testimonials-widget')."</a></h2>";

	$num_testimonials = testimonialswidget_count();
	
	if(!$num_testimonials) {
		$display .= "<p>".__('No testimonials in the database', 'testimonials-widget')."</p>";

		$display .= "</div>";
	
		$display .= "<div id=\"addnew\" class=\"wrap\">\n<h2>".__('Add new testimonial', 'testimonials-widget')."</h2>";
		$display .= testimonialswidget_editform();
		$display .= "</div>";

		echo $display;
		return;
	}

	global $wpdb;

	$sql = "SELECT testimonial_id, testimonial, author, source, tags, public
		FROM " . $wpdb->prefix . "testimonialswidget";
		
	$option_selected = array (
		'testimonial_id' => '',
		'testimonial' => '',
		'author' => '',
		'source' => '',
		'time_added' => '',
		'time_updated' => '',
		'public' => '',
		'ASC' => '',
		'DESC' => '',
	);
	if(isset($_REQUEST['orderby'])) {
		$sql .= " ORDER BY " . $_REQUEST['orderby'] . " " . $_REQUEST['order'];
		$option_selected[$_REQUEST['orderby']] = " selected=\"selected\"";
		$option_selected[$_REQUEST['order']] = " selected=\"selected\"";
	}
	else {
		$sql .= " ORDER BY testimonial_id ASC";
		$option_selected['testimonial_id'] = " selected=\"selected\"";
		$option_selected['ASC'] = " selected=\"selected\"";
	}
	
	if(isset($_REQUEST['paged']) && $_REQUEST['paged'] && is_numeric($_REQUEST['paged']))
		$paged = $_REQUEST['paged'];
	else
		$paged = 1;

	$limit_per_page = 20;
		
	
	
	$total_pages = ceil($num_testimonials / $limit_per_page);
	
	
	if($paged > $total_pages) $paged = $total_pages;

	$admin_url = get_bloginfo('wpurl'). "/wp-admin/admin.php?page=testimonials-widget";
	if(isset($_REQUEST['orderby']))
		$admin_url .= "&orderby=".$_REQUEST['orderby']."&order=".$_REQUEST['order'];
	
	$page_nav = testimonialswidget_pagenav($total_pages, $paged, 2, 'paged', $admin_url);
	
	$start = ($paged - 1) * $limit_per_page;
		
	$sql .= " LIMIT {$start}, {$limit_per_page}"; 

	// Get all the testimonials from the database
	$testimonials = $wpdb->get_results($sql);
	
	foreach($testimonials as $testimonial_data) {
		if($alternate) $alternate = "";
		else $alternate = " class=\"alternate\"";
		$testimonials_list .= "<tr{$alternate}>";
		$testimonials_list .= "<th scope=\"row\" class=\"check-column\"><input type=\"checkbox\" name=\"bulkcheck[]\" value=\"".$testimonial_data->testimonial_id."\" /></th>";
		$testimonials_list .= "<td>" . $testimonial_data->testimonial_id . "</td>";
		$testimonials_list .= "<td>";
		$testimonials_list .= wptexturize(nl2br(make_clickable($testimonial_data->testimonial)));
    	$testimonials_list .= "<div class=\"row-actions\"><span class=\"edit\"><a href=\"{$admin_url}&action=edittestimonial&amp;id=".$testimonial_data->testimonial_id."\" class=\"edit\">".__('Edit', 'testimonials-widget')."</a></span> | <span class=\"trash\"><a href=\"{$admin_url}&action=deltestimonial&amp;id=".$testimonial_data->testimonial_id."\" onclick=\"return confirm( '".__('Are you sure you want to delete this testimonial?', 'testimonials-widget')."');\" class=\"delete\">".__('Delete', 'testimonials-widget')."</a></span></div>";
		$testimonials_list .= "</td>";
		$testimonials_list .= "<td>" . make_clickable($testimonial_data->author);
		if($testimonial_data->author && $testimonial_data->source)
			$testimonials_list .= " / ";
		$testimonials_list .= make_clickable($testimonial_data->source) ."</td>";
		$testimonials_list .= "<td>" . implode(', ', explode(',', $testimonial_data->tags)) . "</td>";
		if($testimonial_data->public == 'no') $public = __('No', 'testimonials-widget');
		else $public = __('Yes', 'testimonials-widget');
		$testimonials_list .= "<td>" . $public  ."</td>";
		$testimonials_list .= "</tr>";
	}
	
	if($testimonials_list) {
		$testimonials_count = testimonialswidget_count();

		$display .= "<form id=\"testimonialswidget\" method=\"post\" action=\"".get_bloginfo('wpurl')."/wp-admin/admin.php?page=testimonials-widget\">";
		$display .= "<div class=\"tablenav\">";
		$display .= "<div class=\"alignleft actions\">";
		$display .= "<select name=\"bulkaction\">";
		$display .= 	"<option value=\"0\">".__('Bulk Actions')."</option>";
		$display .= 	"<option value=\"delete\">".__('Delete', 'testimonials-widget')."</option>";
		$display .= 	"<option value=\"make_public\">".__('Make public', 'testimonials-widget')."</option>";
		$display .= 	"<option value=\"keep_private\">".__('Keep private', 'testimonials-widget')."</option>";
		$display .= "</select>";	
		$display .= "<input type=\"submit\" name=\"bulkactionsubmit\" value=\"".__('Apply', 'testimonials-widget')."\" class=\"button-secondary\" />";
		$display .= "&nbsp;&nbsp;&nbsp;";
		$display .= __('Sort by: ', 'testimonials-widget');
		$display .= "<select name=\"orderby\">";
		$display .= "<option value=\"testimonial_id\"{$option_selected['testimonial_id']}>".__('Testimonial', 'testimonials-widget')." ID</option>";
		$display .= "<option value=\"testimonial\"{$option_selected['testimonial']}>".__('Testimonial', 'testimonials-widget')."</option>";
		$display .= "<option value=\"author\"{$option_selected['author']}>".__('Author', 'testimonials-widget')."</option>";
		$display .= "<option value=\"source\"{$option_selected['source']}>".__('Source', 'testimonials-widget')."</option>";
		$display .= "<option value=\"time_added\"{$option_selected['time_added']}>".__('Date added', 'testimonials-widget')."</option>";
		$display .= "<option value=\"time_updated\"{$option_selected['time_updated']}>".__('Date updated', 'testimonials-widget')."</option>";
		$display .= "<option value=\"public\"{$option_selected['public']}>".__('Visibility', 'testimonials-widget')."</option>";
		$display .= "</select>";
		$display .= "<select name=\"order\"><option{$option_selected['ASC']}>ASC</option><option{$option_selected['DESC']}>DESC</option></select>";
		$display .= "<input type=\"submit\" name=\"orderbysubmit\" value=\"".__('Go', 'testimonials-widget')."\" class=\"button-secondary\" />";
		$display .= "</div>";
		$display .= '<div class="tablenav-pages"><span class="displaying-num">'.sprintf(_n('%d testimonials', '%d testimonials', $testimonials_count, 'testimonials-widget'), $testimonials_count).'</span><span class="pagination-links">'. $page_nav. "</span></div>";
		$display .= "<div class=\"clear\"></div>";	
		$display .= "</div>";
		

		
		$display .= "<table class=\"widefat\">";
		$display .= "<thead><tr>
			<th class=\"check-column\"><input type=\"checkbox\" onclick=\"testimonialswidget_checkAll(document.getElementById('testimonialswidget'));\" /></th>
			<th>ID</th><th>".__('The testimonial', 'testimonials-widget')."</th>
			<th>
				".__('Author', 'testimonials-widget')." / ".__('Source', 'testimonials-widget')."
			</th>
			<th>".__('Tags', 'testimonials-widget')."</th>
			<th>".__('Public?', 'testimonials-widget')."</th>
		</tr></thead>";
		$display .= "<tbody id=\"the-list\">{$testimonials_list}</tbody>";
		$display .= "</table>";

		$display .= "<div class=\"tablenav\">";
		$display .= '<div class="tablenav-pages"><span class="displaying-num">'.sprintf(_n('%d testimonials', '%d testimonials', $testimonials_count, 'testimonials-widget'), $testimonials_count).'</span><span class="pagination-links">'. $page_nav. "</span></div>";
		$display .= "<div class=\"clear\"></div>";	
		$display .= "</div>";

		$display .= "</form>";
		$display .= "<br style=\"clear:both;\" />";

	}
	else
		$display .= "<p>".__('No testimonials in the database', 'testimonials-widget')."</p>";



	$display .= "</div>";
	
	$display .= "<div id=\"addnew\" class=\"wrap\">\n<h2>".__('Add new testimonial', 'testimonials-widget')."</h2>";
	$display .= testimonialswidget_editform();
	$display .= "</div>";
	

	echo $display;

}


function testimonialswidget_admin_footer()
{
	?>
<script type="text/javascript">
function testimonialswidget_checkAll(form) {
	for (i = 0, n = form.elements.length; i < n; i++) {
		if(form.elements[i].type == "checkbox" && !(form.elements[i].hasAttribute('onclick'))) {
				if(form.elements[i].checked == true)
					form.elements[i].checked = false;
				else
					form.elements[i].checked = true;
		}
	}
}
</script>

	<?php
}

add_action('admin_footer', 'testimonialswidget_admin_footer');

?>
