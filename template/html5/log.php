<?php
/*
 * Copyright (c) 2011, Josef Kufner  <jk@frozen-doe.net>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

function TPL_html5__logview__log($t, $id, $d, $so)
{
	extract($d);

	echo	"<div class=\"logview_log\" id=\"", $id, "\"><tt>\n";

	$re_class = array(
		'error' => '/ Error:/i',
		'warning' => '/PHP Notice:| Warning:/i',
		'debug' => '/ Debug:/i',
	);

	foreach ($lines as $offset => $line) {

		$line_class = '';
		foreach ($re_class as $class => $re) {
			if (preg_match($re, $line)) {
				$line_class = $class;
				break;
			}
		}

		echo "<div class=\"$line_class\">";
		if ($line != '') {
			echo "<a name=\"byte", $offset, "\" href=\"", filename_format($line_link, array('name' => $name, 'offset' => $offset)),
				"\" class=\"mark\" title=\"", sprintf(_('Byte %s.'), $offset), "\">#</a> ",
				str_replace("\040\040", "\040&nbsp;", htmlspecialchars($line));
		}
		echo "</div>\n";
	}

	echo "</tt></div>\n";
}


