<?php

try
{
//	$conn	= new MongoClient('127.0.0.1:27017');
//	$conn	= new MongoClient("mongodb://mylms:s3rv3rppdb4dm1nj4b4r@127.0.0.1:27017");
//	$conn   = new MongoClient("mongodb://mylms:s3rv3rppdb4dm1nj4b4r@10.10.10.25:27017");
// 	$conn   = new \MongoClient("mongodb://betuah:br4v0s34m0l3c@mongodb:27017");

	$conn   = new MongoDB\Driver\Manager(
		'mongodb+srv://betuah:br4v0s34m0l3c@localhost/lms?retryWrites=true&w=majority'
	);

	$db		= $conn->lms;
}
catch(Exception $e)
{
	// echo $e->getMessage();
	echo "An error has occured";
}
?>
