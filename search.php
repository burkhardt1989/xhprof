<?php
include_once 'config.php';

$dir = $xhprof_env['url_dir'];
$files = scandir($dir);
foreach ($files as $file) {
	if (in_array($file, array('.', '..'))) {
		continue;
	}
	$url = "{$xhprof_env['domain']}/xhprof/data/{$file}";
	echo "<a url={$url}>{$file}</a>";
}