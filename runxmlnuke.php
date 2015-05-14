#!/usr/bin/env php
<?php

$BASEROOT = __DIR__ . "/Scripts";

// run
if (count($argv) < 4)
{
	echo "-------------------------\n";
	echo "runscript by JG (2015)\n";
	echo "-------------------------\n";

	echo "This script enables you run XMLNuke pages or modules directly from the command line\n";
	echo "The default result is XML (raw=xml) but you can get JSON (raw=json)\n";
	echo "\n";
	echo "USAGE:\n";
	echo "   You have to pass key-value pair for each parameter you want to use. \n";
	echo "\n";
	echo "   runxmlnuke.sh script [path_to_your_site] [arguments]\n";
	echo "\n";
	echo "SCRIPTS AVAILABLE:\n";

	$scripts = glob( "$BASEROOT/*.cmd.php" );
	foreach ($scripts as $script)
	{
		echo " - " . str_replace(".cmd.php", "", basename($script)) . "\n";
	}
	echo "\n";
	echo "EXAMPLE:\n";
	echo "   runxmlnuke.sh xmlnuke /Projects/Sample/httpdocs site=sample xml=home lang=en-us\n";
	echo "\n";
	die();
}

$XMLNUKECMDLINE = "$BASEROOT/" . $argv[1] . ".cmd.php";

if (!file_exists($XMLNUKECMDLINE))
{
	echo "ERROR: Script '" . $argv[1] . ".cmd.php' not found. \n";
	die();
}

if (!file_exists($argv[2] . "/config.inc.php"))
{
	echo "ERROR: File config.inc.php not found in '" . $argv[2] . "' \n";
	die();
}

chdir($argv[2]);

include $XMLNUKECMDLINE;
