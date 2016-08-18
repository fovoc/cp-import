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
	
	$form = '<select name="course">';
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
	
	

	
	///Sends Enrollment conf !!
	//Sends duplicate enrollment conformations
	//First change
	echo $erMsg;
	echo '<div class="wrap">';
	echo '
	<h2><a href="'.plugin_dir_url(__FILE__).'example.csv">Example File</a></h2>
	<br/>
	
	<br/>
    <form enctype="multipart/form-data" action="'.plugin_dir_url(__FILE__).'includes/uploads.php" method="post">'.getCourses().'<br/>
    <!--<input type="text" name="FileTitle"  />-->
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
    Choose a file to upload: <input name="uploaded_file" type="file" />
    <input type="submit" value="Upload" />
  </form> ';
	echo '</div>';
}
?>