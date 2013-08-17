fasterPHP
=========

a tiny library PHP to manipulate databases, uploads and mail.

Getting started
===============
1. Edit 'config.php' and change the default constants for the default params of **Database** class (optional) :
	- **DB_HOST  :** database host
	- **DB_LOGIN  :** database host login
	- **DB_PASSWORD :** database host password
	- **DB_NAME :** database host password


2. Link faster fasterPHP to your project :
	require ('fasterPHP/faster.php');
	
Usage
=====

Database
--------
To understand the 3 classes **Database**, **Table** and **Row**, 
look at the following example which does the same thing but differently with the 3 classes. 
The example is simple : we have a database (with connexion params in config.php), an empty table 'users' and we want to add/modify and delete a user 'bob'.

### Database class
	$db = new Database();

	$table = 'users';

	// add :
	$user = array(
		'id'	=> '',
		'name' 	=> 'Bob',
		'age'	=> 25,
		'email'	=> 'bob@bob.com'
	);

	$db->add( $table , $user );

	// find :
	$users = $db->find( array( 'table'=>$table ) );

	echo count( $users ); // 1
	echo $users[0]['name']; // Bob

	// modify
	$newFields = array(
		'name'	=> 'Bobby'
	);

	$db->set( array( 'table' => $table , 'fields' => $newFields , 'where' => "name='Bob'") );

	$query = array( 'table' => $table , 'where' => "name='Bobby'");
	$user = $db->findOne( $query );
	echo $user['name']; // Bobby

	// delete
	$db->remove( $query );

### Table class
	$users = new Table('users');

	// add :
	$user = array(
		'id'	=> '',
		'name' 	=> 'Bob',
		'age'	=> 25,
		'email'	=> 'bob@bob.com'
	);

	$users->add( $user );

	// find :
	$usersList = $users->find();

	echo count( $usersList ); // 1
	echo $usersList[0]['name']; // Bob

	// modify
	$newFields = array(
		'name'	=> 'Bobby'
	);

	$users->set( array( 'fields' => $newFields , 'where' => "name='Bob'") );

	$query = array( 'where' => "name='Bobby'");
	$user = $users->findOne( $query );
	echo $user['name']; // Bobby

	// delete
	$users->remove( $query );


### Row class
	$users = new Table('users');

	// add :
	$users->add(
		array(
			'id'	=> '',
			'name' 	=> 'Bob',
			'age'	=> 25,
			'email'	=> 'bob@bob.com'
		);
	);

	$user = new Row( 'id' , $users->lastInsertId() , $users ); 
	/* lastInsertId() : 
	Database extends PDO and Table extends Database, 
	so Table extends... PDO ! right.
	So Database and Table inherit methods from PDO
	read PDO doc for more information on lastInsertId() and other methods*/

	echo $user->get('name'); // Bob

	// modify 1 : from Row
	$user->set( array('name'=>'Bobby') );
	echo $user->get('name'); // Bobby

	// modify 2 : from Table
	$users->set( array( 'fields' => array('name'=>'Bobby') , 'where' => "name='Bob'") );
	$user->refresh();
	echo $user->get('name'); // Bobby

	// delete
	$user->remove();


Upload class
------------
This is a basic example. Imagine you've just submit a form with an input(type=file, name=myFile)
	
	# type wanted : image (default)
	# size max : 5mo (default)

	$file = new Upload($_FILES['myFile']);

	if ( !$file->error('type') && !$file->error('size') ){

		$newName = str_replace(' ','-',$file->get('name') );
		$path = 'uploads/';
		
		if($file->moveTo( $path , $newName )){
			echo 'Success !';
		}else{
			echo 'Oups... rights problem on the server';
		}
	}else if( !$file->error('type') ){
		echo 'Hum... I said only images please...';
	}else if(){
		echo 'Hum... I said 5mo max please...';
	}

	# type wanted : document 
	# size max : 10mo
	$types = array('.doc','.pdf','.txt');

	$file = new Upload($_FILES['myFile'], 10 , $types);
	...

Mail class
----------
	$mail = new Mail(array(
		'to' => 'foo@bar.com',
		'cc' => 'bar@foo.com, foo@foo.com',
		'from' => 'bob@bob.com',
		'subject' => 'Hey folks !',
		'fromName' => 'Bob',
		'message' => 'Hey folks. Check my attachement.'
	));

	# oups I forgot the attachement

	$mail->set('attachment','uploads/mydoc.pdf');
	$mail->set('attachmentName', 'my doc');

	if( $mail->send() ){
		$send = 'Email sent.';
	}else{
		echo 'Error...';
	}	
