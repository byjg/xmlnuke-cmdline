<?php

foreach ($argv as $pair)
{
	$arPair = explode("=", $pair);
	if (sizeof($arPair) > 1)
	{
		$_REQUEST[$arPair[0]] = $arPair[1];
		$_GET[$arPair[0]] = $arPair[1];
	}
}
$_SERVER['QUERY_STRING'] = implode('&', $argv);
$_SERVER['REQUEST_URI'] = 'job.cmd.php';

#############################################
# To create a XMLNuke capable PHP5 page
#
require_once("xmlnuke.inc.php");
#############################################

$svcname = (array_key_exists("service", $_REQUEST) ? $_REQUEST['service'] : '');
if ($svcname == "")
{
	die("Error: Paramenter 'service' is required and must contain a full namespace for the class\n");
}

$svcname = str_replace('.', '\\', $svcname);

$service = new $svcname();

try
{
	$continue = $service->execute();
}
catch (Exception $ex)
{
	echo "Error: " . $ex->getMessage() . "\n";
	echo "Backtrace:\n";
	$backTrace = $ex->getTrace();
	$qty = 0;
	foreach ($backTrace as $trace)
	{
		echo "[" . (count($backTrace) - $qty++) . "] " . $trace['file'] . ":" . $trace['line'] . "\n";
	}
}

