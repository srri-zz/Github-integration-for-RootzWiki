<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$('span[id="filename"]').click(function() {
		$(this).next("#files").slideToggle('slow');
	});	
});
</script>
<style>
body {
	padding: 0px;
	margin: 0px;
}
#added {
	background-color: #ddffde;
	display: block;
	padding-left: 4px;
}
#deleted {
	background-color: #ffdddc;
	display: block;
	padding-left: 4px;
}
#nochange {
	display: block;
	padding-left: 4px;
}
#filename {
	display: block;

	border-top: 1px dashed;
	padding-left: 4px;
	cursor:pointer;
}
#filename:hover {
	background-color: #EDEDED;
}
#filename a {
	float: right;
	text-decoration: none;
	color: red;
	border-left: 1px solid black;
	padding-left: 4px;
	padding-right: 4px;
}
#files {
	font-size: 9pt;
	padding: 0px;
	display: none;
	background-color: #FAFAFA;
}
</style>
</head>
<body>

<?php
$urlbase = "http://github.com/api/v2/json/commits/show";
$username = htmlspecialchars(stripslashes(addslashes($_POST['username'])));
$project = htmlspecialchars(stripslashes(addslashes($_POST['projectname'])));
$hash = htmlspecialchars(stripslashes(addslashes($_POST['hash'])));
$urlfull = preg_replace("/ /", "-", "$urlbase/$username/$project/$hash");
$response = get_headers($urlfull);
if ( $response[0] == "HTTP/1.1 404 Not Found") { 
die("<center><br><br>somthing went wrong. did you enter the right info?<br><br>$response[0]<br>username - $username<br>project - $project<br>hash - $hash<br><br>$urlfull</center>");
} 
else {
	echo "username: $username | project name: $project <br>";
	$contents = file_get_contents($urlfull);
	$decode = json_decode($contents);
	$getfilecount = count($decode->commit->modified); 
	for ($i = 0; $i <= $getfilecount - 1; $i++) {
		$filename = $decode->commit->modified[$i]->filename;
		$diff = $decode->commit->modified[$i]->diff;
		$lines = explode("\n", $diff);
		echo "<span id=\"filename\">$filename</span><div id=\"files\"><pre>";
			foreach ($lines as $line) { 
				$getsymbol = substr($line, 0, 1);
				if ($getsymbol == "+") { echo "<span id=\"added\">$line</span>"; }
				else if ($getsymbol == "-") { echo "<span id=\"deleted\">$line</span>"; }
				else { echo "<span id=\"nochange\">$line</span>"; }
			}
			echo "</pre></div>";
	}
}
?>
</body>
</html>