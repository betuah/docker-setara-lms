<?php
// $numbers = range(1, 5);
// shuffle($numbers);
// foreach ($numbers as $number) {
//     echo "$number ";
// }
?>

<?php  

//connect to the database 
// $connect = mysql_connect("localhost","username","password"); 
// mysql_select_db("mydatabase",$connect); //select the table 
// 
if (isset($_POST['Submit'])) {
	# code...
if ($_FILES['csv']['size'] > 0) { 

    //get the csv file 
    $filename = $_FILES['csv']['tmp_name']; 

    $file = fopen($filename, "r");
    while (($getData = fgetcsv($file, 10000, "\t")) !== FALSE)
     {

   		echo "<pre>";
        print_r($getData);
        echo "</pre>";
     }
	
     fclose($file);

}
}


?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
<title>Import a CSV File with PHP & MySQL</title> 
</head> 

<body> 

<?php if (isset($_GET[success])) { echo "<b>Your file has been imported.</b><br><br>"; } //generic success notice ?> 

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
  Choose your file: <br /> 
  <input name="csv" type="file" id="csv" /> 
  <input type="submit" name="Submit" value="Submit" /> 
</form> 

</body> 
</html> 