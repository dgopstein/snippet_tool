<?php 
	$time = filter_input(INPUT_GET, "time");
	$id = filter_input(INPUT_GET, "user");
	echo $time;
	echo $id;
	$file = fopen("tmp$id.xml", "w");
	$txt="<?xml version='1.0' standalone='yes'?>\n<Timers>\n<Timer>\n<time>$time</time>\n</Timer>\n</Timers>";
	fwrite($file, $txt);
	fclose($file);
?> 