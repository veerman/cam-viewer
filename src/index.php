<?php

function getRootDirectories($cwd = '*'){
	return glob($cwd, GLOB_ONLYDIR|GLOB_NOSORT);
}

session_start();

if ($_POST['password'] == "mypassword"){
	$_SESSION['loggedIn'] = 1;
}

?>
<html>
	<head>
		<script src="//code.jquery.com/jquery-2.2.0.min.js"></script>
		<script>
			var last_time = 0;

			// from http://stackoverflow.com/questions/4656843/jquery-get-querystring-from-url
			function getUrlVars(){
				var vars = [], hash;
				var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
				for(var i = 0; i < hashes.length; i++){
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
				}
				return vars;
			}

			function loadImages(){
				$('#loadmore').prop('disabled', true);
				$('#loadmore').val('Loading Images...');

				var qs = getUrlVars();

				$.getJSON('images.php?max=50&search=' + (qs['search'] !== undefined ? qs['search'] : '') + '&before=' + last_time, function(data){
					var html = [];
					$(data).each(function(index, group){
						var first_file_time = Object.keys(group)[0];
						var first_file = group[first_file_time];
						var date = new Date(first_file_time * 1000);
						var date_str = date.toDateString() + ' ' + date.toLocaleTimeString(); // date.toLocaleString()
						html.push('<span>' + date_str + '</span>');
						html.push('<p class="filegroup">');
						$.each(group, function(time, file){
							html.push('<img src="' + file + '" width="160" />&nbsp;');
							last_time = time;
						});
						html.push('</p>');
					});
					$('#images').append(html.join(''));
					$('#loadmore').removeAttr('disabled');
					$('#loadmore').val('Load More');
				});
			}

			$(function(){
				$(document).on('click', '.filegroup', function(){
					if ($(this).find('img:first').attr('width') == 160)
						$(this).find('img').attr('width','640');
					else
						$(this).find('img').attr('width','160');
				});

				$(document).on('click', '#loadmore', function(){
					loadImages();
				});

				if ($('#loadmore').length){
					loadImages();
				}
			});
		</script>
	</head>
	<body>
		<?php
			if ($_SESSION['loggedIn'] !== 1){
		?>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
				Password: <input type="password" name="password" />&nbsp;
				<input type="submit" value="Submit" />
			</form>
		<?php
			}else{
		?>
			<ul>
				<?
				foreach (getRootDirectories() as $dir){
				?>
					<li><a href="?search=<?=$dir?>"><?=$dir?></a></li>
				<?
				}
				?>
			</ul>
			<div id="images"></div>
			<br />
			<input type="button" id="loadmore" value="Load More" style="width:260px;height:30px;" />
		<?php
			}
		?>
	</body>
</html>