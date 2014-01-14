<?php
/*
 * Copyright (c) 2012, Josef Kufner  <jk@frozen-doe.net>
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


function TPL_html5__logview__menu($t, $id, $d, $so)
{
	extract($d);

	echo "<ul class=\"logview_menu\" id=\"", $id, "\">\n";

	foreach ($all_log_cfg as $name => $cfg) {
		echo "<li>";
		printf(_('<a href="%s">%s</a>: %s'),
			filename_format($link, array('name' => $name, 'offset' => 'eof')),
			$name, $cfg['description']);
		echo "<br>\n\t<small>";
		printf(_('File: <tt>%s</tt>'), $cfg['file']);
		echo "</small></li>\n";
	}

	echo "</ul>\n";
}



