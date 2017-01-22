<?php




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
		 $userdata = array(
    	'user_login'  =>  $email[0],
			'user_email'  =>  $email[0],
			'first_name'  =>  $email[1],
			'last_name'  =>  $email[2],
    	'user_pass'  =>  wp_generate_password(),
			);
		$user_id = wp_insert_user( $userdata );

		if(is_int($user_id)){
			 update_user_meta( $user_id, 'billing_company', $email[3] );
			 $c[] = array($userdata['user_login'],$userdata['user_pass'],$user_id,$userdata['first_name'],$userdata['last_name'],get_user_meta($user_id, 'billing_company', true));
			 }else{
				//$password = $user_id;
				//$password = $user_id['user_pass']->errors;
				$password = $user_id->errors;
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

			$line = array('Username/Email','Password','First','Last','Company');
			fputcsv($temp_memory, $line, $delimiter);

			foreach ($input_array as $line) {


				$line = array($line[0],$line[1],$line[3],$line[4],$line[5]);
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
