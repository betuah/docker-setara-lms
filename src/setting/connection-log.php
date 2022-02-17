<?php

try
{
	// $connLog	= new MongoClient('127.0.0.1:27017');
	// $connLog	= new MongoClient("mongodb://myppdb:s3rv3rppdb4dm1nj4b4r@10.10.10.13:27017");
	// $connLog  	= new MongoClient('mongodb://10.10.10.13', array('username'=>'loglms','password'=>'s1m0l3kdb'));
    // $connLog       = new MongoClient('mongodb://127.0.0.1', array('username'=>'log_lms','password'=>'s3t4r@Log_lms'));
	//$connLog  	= new MongoClient('mongodb://103.52.145.197:8888', array('username'=>'loglms','password'=>'s1m0l3kdb'));
	$connLog   = new MongoDB\Driver\Manager(
		'mongodb+srv://betuah:br4v0s34m0l3c@localhost/log_lms?retryWrites=true&w=majority'
	);
	
	$dbLog		= $connLog->log_lms;
}
catch(Exception $e)
{
	// echo $e->getMessage();
	echo "An error has occured";
}

// try {
// 	db.log_access.deleteMany({ $and : [{"link" : { $not : /.*lms.seamolec.*/i }}, {"link" : { $not : /.*jass.disdik.*/i }}] });
//  } catch (e) {
// 	print (e);
//  }
?>
