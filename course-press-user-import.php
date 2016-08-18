<?php
/*
Plugin Name: CP import - CoursePress Student Import
Plugin URI: http://sapphirebd.com/
Version: 0.1
Author: Christian Campbell
Description: This Plugin imports email addresses, creates users, adds them as students, and Sends an enrollment conformation email. THis plugin is perfect for B2B sales, group trainings, or Course giveaways. 
*/


$erMsg ="";


function getCourses(){
		$myposts = get_posts(array(
			'showposts' => -1,
			'post_type' => 'course',
			'orderby'   => 'ID',
			'order'     => 'ASC',
			)); 
	
	$courses = array();
	foreach($myposts as $course){
		$c = array();
		$ID = $course->ID;
		$title = $course->post_title;
		$c[] = $ID;
		$c[] = $title;
		
		$courses[] = $c;
		}


$form = '<select id="selectbasic" name="course" class="form-control">';
	foreach($courses as $course){
     $form .='<option value="'.$course[0].'">'.$course[1].'</option>';
	}
$form .= '</select>';

return $form;

	}
	
	


/** Step 2 (from text above). */
add_action( 'admin_menu', 'cpim_menu' );

/** Step 1. */
function cpim_menu() {
	add_options_page( 'CP import Options', 'CP import', 'manage_options', 'cpim', 'cpim_menu_options' );
}

/** Step 3. */
function cpim_menu_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
echo '<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';


echo'<div class="jumbotron">
  <h1> Hello, world!</h1>
  <p> This is the beta version of this plugin. Download the example file to see the format your data should be in.</p>
  <p> Example File<a href="'.plugin_dir_url(__FILE__).'example.csv"><span class="dashicons dashicons-download"></span></a>
</div>';



echo '<form class="form-horizontal" enctype="multipart/form-data" action="'.plugin_dir_url(__FILE__).'includes/uploads.php" method="post">
<fieldset>


<legend>Course Press Import Users</legend>
<div class="form-group">
  <label class="col-md-4 control-label" for="selectbasic">Select Course to add Students</label>
  <div class="col-md-4">
	'.getCourses().'
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="filebutton">Upload Email</label>
  <div class="col-md-4">
  <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
    <input id="filebutton" name="uploaded_file" class="input-file" type="file">
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button id="singlebutton" type="submit" class="btn btn-primary">Import</button>
  </div>
</div>

</fieldset>
</form>

';	
		
}
?>