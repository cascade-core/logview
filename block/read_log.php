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
		'filename' => false,		// Filename to show ('file_map' will be ignored if this is set).
		'name' => false,		// Name of log to show (looked up from 'file_map').
		'file_map' => false,		// Mapping log name to real filename.
		'offset' => 0,			// Start from byte 'offset'
		'max_lines' => 250,		// Max. lines count
		'snap_url' => null,		// Snap to line start -- redirect url.
	);

	protected $outputs = array(
		'file' => true,			// Name of file
		'name' => true,			// Name of log (before applying 'file_map')
		'lines' => true,		// Loaded lines (in array).
		'offsets' => true,		// Offsets: First line, line after last line, eof.
		'count' => true,		// Count of loaded lines.
		'title' => true,		// Nice title
		'done' => true,
	);


	public function main()
	{
		$filename = $this->in('filename');
		$name = $this->in('name');
		$file_map = $this->in('file_map');
		$offset = $this->in('offset');
		$max_lines = $this->in('max_lines');
		$snap_url = $this->in('snap_url');

		// Get filename
		if ($filename !== false) {
			// Filename specified
			$file = $filename;
		} else {
			// Lookup from file_map
			if (!array_key_exists($name, $file_map)) {
				error_msg('Requested unknown log file: %s', $name);
				return;
			}
			$file = filename_format($file_map[$name]);
		}

		// Build log title
		$this->out('title', sprintf(_('Log %s'), basename($file)));
		$this->out('name', $name);

		// Open log
		$f = fopen($file, 'rt');
		if ($f === FALSE) {
			error_msg('Failed to open log file: %s', $file);
			return;
		}

		$stat = fstat($f);

		// Seek to offset
		if ($offset == 'eof') {
			$offset = $this->find_prev_page_offset($f, $stat['size'], $max_lines);
		} else if ($offset > 0) {
			if ($snap_url != '') {
				fseek($f, min($offset, $stat['size']) - 1, SEEK_SET);
				fgets($f); // read '\n' if we are on begin of line, otherwise read to begin of next line
				$real_offset = ftell($f);
				if ($real_offset != $offset) {
					debug_msg('Snap to byte %s (requested %s).', $real_offset, $offset);
					$url = filename_format($snap_url, array('name' => $name, 'offset' => $real_offset));
					$this->template_option_set('root', 'redirect_url', $url);
					return;
				}
			} else {
				fseek($f, min($offset, $stat['size']), SEEK_SET);
			}
		}
		$begin_offset = ftell($f);

		// Read requested lines
		$p = ftell($f);
		for ($i = 0; $i < $max_lines && ($ln = fgets($f)) !== FALSE; $i++) {
			$lines[$p] = $ln;
			$p = ftell($f);
		}
		$end_offset = ftell($f);

		// Collect offsets
		$offsets = array(
			'begin' => $begin_offset,
			'end' => $end_offset,
			'prev' => $this->find_prev_page_offset($f, $begin_offset, $max_lines),
			'eof' => $stat['size'],
		);

		$this->out('file', $file);
		$this->out('lines', $lines);
		$this->out('offsets', $offsets);
		$this->out('count', $i);
		$this->out('done', true);

		fclose($f);
	}

	private function find_prev_page_offset($f, $begin_offset, $max_lines, $step = 4096)
	{
		$line_offsets = array();
		$p = $begin_offset;

		$min_line_offset = $p;
		$can_read = true;

		while (count($line_offsets) <= $max_lines && $min_line_offset > 0 && $can_read) {
			// Step backward in file
			if ($p > $step) {
				$p -= $step;
				fseek($f, $p, SEEK_SET);
				fgets($f);
			} else {
				$p = 0;
				fseek($f, $p, SEEK_SET);
			}

			// Find all line begins from $p to $min_line_offset
			$line_begin = ftell($f);
			while ($line_begin < $min_line_offset) {
				$line_offsets[] = $line_begin;
				if (fgets($f) === FALSE) {
					$can_read = false;
					break;
				}
				$line_begin = ftell($f);
			}

			$min_line_offset = $p;
		}

		if (empty($line_offsets)) {
			return 0;
		}

		// Sort and get offset of the first of last $max_lines lines
		sort($line_offsets, SORT_NUMERIC);
		$i = max(0, count($line_offsets) - $max_lines);
		return $line_offsets[$i];
	}
}


