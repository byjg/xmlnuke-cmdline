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

$service = new $svcname();

$continue = $service->execute();

