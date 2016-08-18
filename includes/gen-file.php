<?php


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



function checkFile($files){
	//check if file is empty or has an error. 
	if((!empty($files)) && ($files['error'] == 0)) {
	$filename = basename($files['name']);
  	$ext = substr($filename, strrpos($filename, '.') + 1);
	if (($ext == "csv") && ($files["type"] == "text/csv") && ($files["size"] < 350000)) {
		return $files;
			
		}//else Report error with filetype
	}else{
		return false;
		}//else Report error uploading file
}

function getCid($post){
	$id = $post["course"];
	if(is_numeric($id)){
		return $id;
		}else{
			exit;
			}
	}	
	

function createUsers($csv){
	foreach($csv as $email){
		 $username = $email[0];
		 $email = $username;
		 $password = wp_generate_password(); 
		 $ID = wp_create_user( $username, $password, $email );
		 
		 if(is_int($ID)){
			 $c[] = array($username,$password,$ID);
			 }else{	 
				$password = $ID;
				$password = $password->errors;
				$error = $password['existing_user_login'][0];// Account for Duplicate emails
				if(empty($error)){
					$error = $password['existing_user_email'][0];
					}
				
				$user = get_user_by( 'email', $username );
				$ID = $user->ID;
				$c[] = array($username,$error,$ID);
			 }
		}
	return $c;
	}

function AddAsStudents($Users, $ID){
	foreach($Users as $user){
	 $userID = $user[2];
	 $student = new Student($userID);
	 $is_enrolled = $student->user_enrolled_in_course($ID);
	 if( ! $is_enrolled ) {
       $student->enroll_in_course($ID);
	 		}
		}
	}











function convert_to_csv($input_array, $output_file_name ="MassAddCpStudents.csv", $delimiter =","){
			$temp_memory = fopen('php://memory', 'w');
			
			$line = array('Username/Email','Password');
			fputcsv($temp_memory, $line, $delimiter);
			
			foreach ($input_array as $line) {
				
				
				$line = array($line[0],$line[1]);
			// use the default csv handler
			
			
			fputcsv($temp_memory, $line, $delimiter);
			}
			
			fseek($temp_memory, 0);
			// modify the header to be CSV format
			header('Content-Type: application/csv');
			header('Content-Disposition: attachement; filename="'.$output_file_name.'";');
			// output the file to be downloaded
			fpassthru($temp_memory);

}



 ?>
