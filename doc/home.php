<h1>Getting started</h1>
<ol>
	<li>Edit 'config.php' and change the default constants for the default params of <strong>Database</strong> class (optional) :
		<ul>	
			<li><strong>DB_HOST  :</strong> database host</li>
			<li><strong>DB_LOGIN  :</strong> database host login</li>
			<li><strong>DB_PASSWORD :</strong> database host password</li>
			<li><strong>DB_NAME :</strong> database host password</li>
		</ul>

	<li>Link faster fasterPHP to your project :
		<pre><code>require ('fasterPHP/fasterPHP.php');</code></pre>
	</li>
</ol>
<h1>Usage</h1>
<ul>
	<li><a href="#usage-database">Database</a></li>
	<li><a href="#usage-table">Table</a></li>
	<li><a href="#usage-row">Row</a></li>
	<li><a href="#usage-upload">Upload</a></li>
	<li><a href="#usage-mail">Mail</a></li>
</ul>
<h2>Database</h2>
<p>To understand the 3 classes <strong>Database</strong>, <strong>Table</strong> and <strong>Row</strong>, 
look at the following example which does the same thing but differently with the 3 classes. 
The example is simple : we have a database (with connexion params in config.php), an empty table 'users' and we want to add/modify and delete a user 'bob'.</p>
<h3 id="usage-database">Database class</h3>
<pre>
<code>$db = new Database();

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
</code>
</pre>
<h3 id="usage-table">Table class</h3>
<pre>
<code>$users = new Table('users');

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
</code>
</pre>

<h3 id="usage-row">Row class</h3>
<pre>
<code>$users = new Table('users');

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
</code>
</pre>

<h2 id="usage-upload">Upload class</h2>
<p>This is a basic example. Imagine you've just submit a form with an input[type=file][name=myFile]</p>
<pre>
<code>// type wanted : image (default)
// size max : 5mo (default)

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

// type wanted : document 
// size max : 10mo
$types = array('.doc','.pdf','.txt');

$file = new Upload($_FILES['myFile'], 10 , $types);
...
</code>
</pre>

<h2 id="usage-mail">Mail class</h2>
<p>This is a basic example.</p>
<pre>
<code>$mail = new Mail(array(
	'to'	=> 'foo@bar.com',
	'cc'	=> 'bar@foo.com, foo@foo.com',
	'from'	=> 'bob@bob.com',
	'subject'	=> 'Hey folks !',
	'fromName'	=> 'Bob',
	'message'	=> '&lt;h1>Hey folks!&lt;h1>&lt;p>Check my attachement.&lt;/p>'
));

//oups I forgot the attachement

$mail->set('attachment','uploads/mydoc.pdf');
$mail->set('attachmentName', 'my doc');

if( $mail->send() ){
	$send = 'Email sent.';
}else{
	echo 'Error...';
}	
</code>
</pre>
