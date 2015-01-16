<?php

function glob_recursive($cwd = '*'){
	$files = glob($cwd."*.{jpg,jpeg}", GLOB_BRACE);
	arsort($files); // preliminary sort required for array_slice
	$files = array_slice($files, 0, MAX);
	$files = array_combine($files, array_map("filectime", $files));
	$dirs = glob($cwd.'*', GLOB_ONLYDIR|GLOB_NOSORT);
	//$dirs = array_combine($dirs, array_map("filectime", $dirs));
	arsort($dirs);
	foreach ($dirs as $dir){
		$files = array_merge($files, glob_recursive($dir.'/'));
		if (count($files) > MAX)
			break;
	}
	arsort($files); // final files sort (newest first)
	return $files;
}

function groupFilesByTime($files){
	$groups = array();
	$last_time = 0;
	$interval = 30; // 30 seconds between groups
	foreach ($files as $file => $time){
		$difference = $prev_time - $time;
		if (abs($difference) < $interval)
			$groups[count($groups) - 1][$time] = $file;
		else
			$groups[][$time] = $file;
		$prev_time = $time;
	}
	return $groups;
}

$search = isset($_GET['search']) ? '*'.$_GET['search'].'*' : '*';
$max = isset($_GET['max']) ? intval($_GET['max']) : 100;
define('MAX', $max);
$files = glob_recursive($search);
$files = array_slice($files, 0, MAX);
$groups = groupFilesByTime($files);

?>
<html>
	<head>
		<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
		<script>
			$(document).ready(function(){
				$('.filegroup').click(function(){
					if ($(this).find('img:first').attr('width') == 160)
						$(this).find('img').removeAttr('width');
					else
						$(this).find('img').attr('width','160');
				});
			});
		</script>
	</head>
	<body>
		<?php
			foreach($groups as $group){
				$first_file = reset($group);
				$first_file_time = key($group);
				//preg_match_all('/[!0-9]*(20[0-9][0-9])([0-9][0-9])([0-9][0-9])([0-9][0-9])([0-9][0-9])([0-9][0-9])/', $first_file, $matches);
				//$first_file_time = strtotime($matches[1][0].'-'.$matches[2][0].'-'.$matches[3][0].' '.$matches[4][0].':'.$matches[5][0].':'.$matches[6][0]);
				echo date('l F j, Y g:i a', $first_file_time);
				echo "<p class='filegroup'>\n";
				foreach ($group as $time => $file){
					echo "<img src='$file' width='160' />&nbsp;";
				}
				echo "</p>\n";
			}
		?>
	</body>
</html>