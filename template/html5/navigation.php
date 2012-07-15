<?php
/*
 * Copyright (c) 2012, Josef Kufner  <jk@frozen-doe.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. Neither the name of the author nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
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
		echo "\t<a href=\"", filename_format($link, array('offset' => 0)), "\">", _('Begin'), "</a>\n";
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
		echo "\t<a href=\"", filename_format($link, array('offset' => $end_offset)), "\">", _('Next page »'), "</a>\n";
	}

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



