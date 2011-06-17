<?php

class ocia_tools {
	/*
	 * @TODO: Finish function "date_convert();"
	 */

	public function date_convert($format1, $format2, $date) {
		// From date, when not time available
		if (strtolower($format1 == 'd-m-y')) {
			$expl = explode("-", $format1);
			$time = mktime(0, 0, 0, $expl[1], $expl[0], $expl[2]);
			return date($format2, $time);
		}
		if (strtolower($format1 == 'm-d-y')) {
			$expl = explode("-", $format1);
			$time = mktime(0, 0, 0, $expl[0], $expl[1], $expl[2]);
			return date($format2, $time);
		}
		if (strtolower($format1 == 'd/m/y')) {
			$expl = explode("/", $format1);
			$time = mktime(0, 0, 0, $expl[1], $expl[0], $expl[2]);
			return date($format2, $time);
		}
		if (strtolower($format1 == 'm/d/y')) {
			$expl = explode("-", $format1);
			$time = mktime(0, 0, 0, $expl[0], $expl[1], $expl[2]);
			return date($format2, $time);
		}

		// When time is available
		if (count(explode(" ", $format1)) == 2) {
			$expl = explode(" ", $format1);
			$date = $expl[0];
			$time = $expl[1];
			// explode date
			if (strtolower($date == 'd-m-y')) {
				$expl = explode("-", $date);
				$date = array("d" => $expl[0], "m" => $expl[1], "y" => $expl[2]);
			}
			if (strtolower($date == 'm-d-y')) {
				$expl = explode("-", $date);
				$date = array("d" => $expl[1], "m" => $expl[0], "y" => $expl[2]);
			}
			if (strtolower($date == 'd/m/y')) {
				$expl = explode("/", $date);
				$date = array("d" => $expl[0], "m" => $expl[1], "y" => $expl[2]);
			}
			if (strtolower($date == 'm/d/y')) {
				$expl = explode("-", $date);
				$date = array("d" => $expl[1], "m" => $expl[0], "y" => $expl[2]);
			}

			if ($time == 'H:i') {

			}
		}
	}

	public function date_now($format) {
		$time = time();
		return date($format, $time);
	}

	public function date_diff($date1, $date2, $format1, $format2, $return_format) {
		/*
		 * TODO: Build function date_diff();
		 */
	}

	public function curl_get_url($url, $optionsarray) {
		if (!function_exists("curl_exec")) {
			return false;
		}
		$ch = curl_init($url);
		if (isset($optionsarray) && !empty($optionsarray)) {
			curl_setopt_array($ch, $optionsarray);
		} else {
			$return = curl_exec($ch);
		}
		return $return;
	}

	public function curl_download_url($url, $dir, $filename, $optionsarray) {
		if (!function_exists("curl_exec")) {
			return false;
		}
		$ch = curl_init($url);
		if (isset($optionsarray) && !empty($optionsarray)) {
			curl_setopt_array($ch, $optionsarray);
		}
		$return = curl_exec($ch);
		if (fopen($dir . $filename, "w+")) {
			$fh = fopen($dir . $filename, "w+");
			fwrite($fh, $return);
			$return = fclose($fh);
			return true;
		} else
			return false;
	}
	public function curl_download_file($url, $file) {
		$out = fopen($file, 'wb');
		if ($out == FALSE){
			#print "File not opened<br>";
			return false;
		} 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FILE, $out);
	   curl_setopt($ch, CURLOPT_HEADER, 0);
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_exec($ch); 
	}

	public function db_connect($host, $user, $pass, $db) {
		if (mysql_connect($host, $user, $pass)) {
			$conn = mysql_connect($host, $user, $pass);
			mysql_select_db($db, $conn);
			return true;
		} else
			return mysql_error();
	}

	public function db_select($db) {
		if (mysql_select_db($db)) {
			return true;
		} else {
			return mysql_error();
		}
	}

	public function db_query($query) {
		$query = mysql_real_escape_string($query);
		if (mysql_query($query))
			return true;
		else
			return mysql_error();
	}

	public function sysinfo_loadavg($param) {
		if (function_exists("sys_getloadavg")) {
			$load = sys_getloadavg();
			return $load[$param];
		} else
			return false;
	}

	/*
	 * @TODO: function "sysinfo_du()" needs testing for the correct return values
	 */

	public function sysinfo_du($dir, $return_format) {
		if (exec("du -s " . $dir, $output)) {
			if ($return_format == 'all') {
				return $output;
			}
			if ($return_format == 'sum') {
				return end($output);
			}
		}
	}

	public function sysinfo_memory($return_format, $perc = true) {
		$predefined = array("used");
		if (exec("cat /proc/meminfo", $output)) {
			if (!in_array($return_format, $predefined)) {
				for ($i = 0; $i < count($output); $i++) {
					if (preg_match('^MemFree:\s+(\d+)\skB$^', $output[$i], $pieces)) {
						$return = $pieces[1];
						break;
					}
				}
			} else {
				if ($return_format == 'used') {
					for ($i = 0; $i < count($output); $i++) {
						if (preg_match('^MemFree:\s+(\d+)\skB$^', $output[$i], $pieces)) {
							$free = $pieces[1];
							break;
						}
					}
					for ($i = 0; $i < count($output); $i++) {
						if (preg_match('^MemTotal:\s+(\d+)\skB$^', $output[$i], $pieces)) {
							$total = $pieces[1];
							break;
						}
					}
					$used = $total - $free;
					$return = $used;
				}
			}
			return $return;
		}
		else
			return false;
	}

}

?>
