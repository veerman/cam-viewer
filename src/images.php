<?php

class camViewer{
	public function __construct(){
	}

	public function glob_recursive($cwd = '*'){
		$files = glob($cwd."*.{jpg,jpeg}", GLOB_BRACE);
		arsort($files); // preliminary sort required for array_slice
		//$files = array_slice($files, 0, MAX);
		$files = array_combine($files, array_map("filectime", $files));
		$dirs = glob($cwd.'*', GLOB_ONLYDIR|GLOB_NOSORT);
		//$dirs = array_combine($dirs, array_map("filectime", $dirs));
		arsort($dirs);
		foreach ($dirs as $dir){
			$files = array_merge($files, $this->glob_recursive($dir.'/'));
			//if (count($files) > MAX)
			//	break;
		}
		arsort($files); // final files sort (newest first)
		return $files;
	}

	public function groupFilesByTime($files, $max){
		$groups = array();
		$last_time = 0;
		$interval = 30; // 30 seconds between groups
		$file_cnt = 0;
		foreach ($files as $file => $time){
			$difference = $prev_time - $time;
			if (abs($difference) < $interval)
				$groups[count($groups) - 1][$time] = $file;
			else{
				if ($file_cnt > $max)
					break;
				$groups[][$time] = $file;
			}
			$prev_time = $time;
		}
		return $groups;
	}

	public function echoJSON($object, $callback = null){
		if (isset($callback)){
			header('Content-Type: text/javascript; charset=utf-8');
			echo $callback.'('.json_encode($object, JSON_UNESCAPED_UNICODE).');';
		}
		else{
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($object, JSON_UNESCAPED_UNICODE);
		}
	}
}

session_start();

if ($_SESSION['loggedIn'] !== 1)
	exit;

$camviewer = new camviewer();

$search = !empty($_GET['search']) ? '*'.$_GET['search'].'*' : '*';
$max = !empty($_GET['max']) ? intval($_GET['max']) : 100;
$before = !empty($_GET['before']) ? intval($_GET['before']) : 0;
define('MAX', $max);
$files = $camviewer->glob_recursive($search);

$start = 0;
if (!empty($before)){ // page results if necessary
	$cnt = 0;
	foreach($files as $file => $time){
		if ($time < $before){
			$start = $cnt;
			break;
		}
		$cnt++;
	}
	if ($start === 0) // start not found, set as high number so array_slice returns empty
		$start = 999999;
}
$files = array_slice($files, $start, MAX*2);

$groups = $camviewer->groupFilesByTime($files, MAX);

$camviewer->echoJSON($groups);

?>