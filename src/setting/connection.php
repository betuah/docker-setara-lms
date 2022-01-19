<?php

try
{
//	$conn	= new MongoClient('127.0.0.1:27017');
	$conn	= new MongoClient("mongodb://mylms:s3rv3rppdb4dm1nj4b4r@127.0.0.1:27017");
//	$conn   = new MongoClient("mongodb://mylms:s3rv3rppdb4dm1nj4b4r@10.10.10.25:27017");
	$db		= $conn->lms;
}
catch(Exception $e)
{
	// echo $e->getMessage();
	echo "An error has occured";
}
?>
