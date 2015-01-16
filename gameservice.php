<?php

// PHP file bij lesopdracht 1 van week 4
// Zet dit bestand in dezelfde directory als .htaccess
// en pas .htaccess aan

if (isset($_GET["id"]))
{
	$id = $_GET["id"];
}
else 
{
	$id = "no id";
}

if (isset($_GET["category"]))
{
	$category = $_GET["category"];
}
else
{
	$category = "no category";
}

if (isset($_GET["type"])) {
	$type = $_GET["type"];
} else {
	$type = "no type";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Gameservice</title>
</head>

<body>
<?php

	echo "<h2>id = $id</h2>";
	echo "<h2>category = $category</h2>";
	echo "<h2>type = $type</h2>";
	
?>
</body>

</html>