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

function TPL_html5__logview__navigation($t, $id, $d, $so)
{
	extract($d);

	echo "<table width=\"100%\" class=\"logview_navigation\" id=\"", $id, "\">\n";
	echo "<col width=\"50%\">\n";
	echo "<col>\n";
	echo "<col width=\"50%\">\n";

	echo "<tr>\n";

	echo "<td rowspan=\"2\" align=\"left\" valign=\"middle\" class=\"prev\">\n";

	// Begin
	if (!$at_begin) {
		echo "\t<a href=\"", filename_format($link, array('name' => $name, 'offset' => 0)), "\">", _('Begin'), "</a>\n";
	}

	// Prev page
	if ($prev_offset > 0) {
		echo "\t<a href=\"", filename_format($link, array('name' => $name, 'offset' => $prev_offset)), "\">", _('« Previous page'), "</a>\n";
	}

	echo "</td>\n";

	// Position
	echo "<td nowrap align=\"center\" class=\"position\">";
	printf(_('Position: <b>%d %%</b> … %d %% (Bytes %s … %s of %s)'),
			100. * $begin_offset / $eof_offset,
			100. * $end_offset / $eof_offset,
		       	$begin_offset, $end_offset, $eof_offset);
	echo "</td>\n";


	echo "<td rowspan=\"2\" align=\"right\" valign=\"middle\" class=\"next\">\n";

	// Next
	if (!$at_eof) {
		echo "\t<a href=\"", filename_format($link, array('name' => $name, 'offset' => $end_offset)), "\">", _('Next page »'), "</a>\n";
	}

	echo "\t<a href=\"", filename_format($link, array('name' => $name, 'offset' => 'eof')), "\">", _('Last page'), "</a>\n";

	echo "</td>\n";

	echo "</tr>\n";
	echo "<tr>\n";

	// File
	echo "<td nowrap align=\"center\" class=\"file\">";
	printf('File: <tt>%s</tt>', htmlspecialchars($file));
	echo "</td>\n";

	echo "</tr>\n";
	echo "</table>\n";
}



