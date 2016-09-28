<?php
function wp_mail()
{
    // Do nothing!
}

error_reporting(0);
//include_once('../wp-includes/option.php');
require_once('../../../../wp-config.php');
include 'gen-file.php';

//Posted files
$files = $_FILES["uploaded_file"];
$post = $_POST;
//Imported users CsV
$impUsers =  array(array( "Username", "Password", "email" ),);


$file = checkFile($files);


if($file){
$ID = getCid($post);
$tmpname = $file['tmp_name'];
//Convert CSV to array
$csv = array_map('str_getcsv', file($tmpname));
//Remove first row in CSV Array
$csv = array_splice($csv, 1);
//Create Users
$Users = createUsers($csv);
//Add users to course
$students = AddAsStudents($Users,$ID);

convert_to_csv($Users);
	}else{



$url = get_site_url().'/wp-admin/options-general.php?page=cpim';
header( 'Location:'.$url ) ;

		}
