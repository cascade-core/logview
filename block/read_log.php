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

class B_logview__read_log extends Block
{

	protected $inputs = array(
		'file' => array(),		// Name of file to show
		'offset' => 0,			// Start from byte 'offset'
		'max_lines' => 1000,		// Max. lines count
		'snap_url' => null,		// Snap to line start -- redirect url.
	);

	protected $outputs = array(
		'lines' => true,		// Loaded lines (in array).
		'begin_offset' => true,		// Offset of the first line.
		'end_offset' => true,		// Offset of line after the last line.
		'at_begin' => true,		// True, if there is nothing before first line in 'lines'.
		'at_eof' => true,		// True, if there is nothing after last line in 'lines'.
		'count' => true,		// Count of loaded lines.
		'done' => true,
	);


	public function main()
	{
		$file = filename_format($this->in('file'));
		$offset = $this->in('offset');
		$max_lines = $this->in('max_lines');
		$snap_url = $this->in('snap_url');

		// Open log
		$f = fopen($file, 'rt');
		if ($f === FALSE) {
			return;
		}

		// Seek to offset
		if ($offset > 0) {
			if ($snap_url != '') {
				fseek($f, $offset - 1, SEEK_SET);
				fgets($f); // read '\n' if we are on begin of line, otherwise read to begin of next line
				$real_offset = ftell($f);
				if ($real_offset != $offset) {
					$url = filename_format($snap_url, array('offset' => $real_offset));
					$this->template_option_set('root', 'redirect_url', $url);
				}
			} else {
				fseek($f, $offset, SEEK_SET);
			}
		}

		for ($i = 0; $i < $max_lines && ($ln = fgets($f)) !== FALSE; $i++) {
			$lines[] = $ln;
		}

		$this->out('lines', $lines);
		$this->out('begin_offset', $offset);
		$this->out('end_offset', ftell($f));
		$this->out('at_begin', $offset == 0);
		$this->out('at_eof', feof($f));
		$this->out('count', $i);
		$this->out('done', true);

		fclose($f);
	}
}


