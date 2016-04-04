<?php
	$version = "HTML-CTPP2-2.8.2";
	get_dirs("http://redmine.communico.pro/projects/html-ctpp2/repository", "/tags/$version/");
	
	function get_dirs($repo, $path, $root = NULL, $x = "") {
		if (!$root)
			$root = $path;
		$localpath = "./".substr($path, strlen($root));
		
		@mkdir($localpath, true);
		chmod($localpath, 0755);
		
		$res = file_get_contents($repo."/show".$path);
		preg_match_all('#icon-folder[^>]+>([^<>]+)</a>#si', $res, $dirs);
		foreach ($dirs[1] as $dir) {
			echo $x.$dir."\n";
			get_dirs($repo, $path."/".$dir, $root, "\t".$x);
		}
		preg_match_all('#icon-file[^>]+>([^<>]+)</a>#si', $res, $files);
		foreach ($files[1] as $file) {
			do {
				if (!file_exists($localpath."/".$file))
					system("wget -qc ".escapeshellarg($repo."/raw/".$path."/".$file)." -O ".escapeshellarg($localpath."/".$file));
				echo $x.$file."\n";
			} while (!file_exists($localpath."/".$file));
		}
	}
