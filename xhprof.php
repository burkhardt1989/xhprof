<?php
include_once 'config.php';

function xhprof_start()
{
	xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY + XHPROF_FLAGS_NO_BUILTINS);
}

function xhprof_stop()
{
	// 检测
	if(empty($_SERVER['REQUEST_URI'])) {
		return;
	}
	// 设置保存xhprof目录
	$sign = date('YmdH');
	// $output_dir = "/data/httpd/operimg/xhprof/{$sign}";
	$output_dir = "{$xhprof_env['log_dir']}/{$sign}";
	if (!file_exists($output_dir)) {
		mkdir($output_dir);
	}
	ini_set('xhprof.output_dir', $output_dir);
	// 保存xhprof
	$xhprof_data = xhprof_disable();
	include_once  "{$xhprof_env['lib_dir']}/utils/xhprof_lib.php";
	include_once  "{$xhprof_env['lib_dir']}/utils/xhprof_runs.php";
	$xhprof_runs = new XHProfRuns_Default();
	$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
	// 记录xhprof查看链接
	$url = "{$xhprof_env['domain']}/xhprof/xhprof_html/index.php?run=$run_id&source=xhprof_foo&sign={$sign}";
	$content = 'Times:'.($xhprof_data['main()']['wt']/1000/1000)."<a href='$url' target='_blank'>".$_SERVER['REQUEST_URI']."</a><br>";
	file_put_contents("{$xhprof_env['url_dir']}/url{$sign}.html", $content, FILE_APPEND);
}

xhprof_start();
register_shutdown_function('xhprof_stop');
