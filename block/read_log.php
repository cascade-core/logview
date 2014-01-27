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

class B_logview__read_log extends \Cascade\Core\Block
{

	protected $inputs = array(
		'log_cfg' => array(),		// Log configuration
		'offset' => 0,			// Start from byte 'offset'
		'snap_url' => null,		// Snap to line start -- redirect url.
	);

	protected $outputs = array(
		'lines' => true,		// Loaded lines (in array).
		'offsets' => true,		// Offsets: First line, line after last line, eof.
		'count' => true,		// Count of loaded lines.
		'title' => true,		// Nice title
		'done' => true,
	);


	public function main()
	{
		$log_cfg = $this->in('log_cfg');
		$offset = $this->in('offset');
		$snap_url = $this->in('snap_url');

		$max_lines = @ $log_cfg['max_lines'];
		if ($max_lines == null) {
			$max_lines = 250;
		}

		// Build log title
		$this->out('title', sprintf(_('Log %s'), basename($log_cfg['file'])));

		// Open log
		$f = fopen($log_cfg['file'], 'rt');
		if ($f === FALSE) {
			error_msg('Failed to open log file: %s', $log_cfg['file']);
			return;
		}

		$stat = fstat($f);

		// Seek to offset
		if ($offset == 'eof') {
			$offset = $this->findPrevPageOffset($f, $stat['size'], $max_lines);
		} else if ($offset > 0) {
			if ($snap_url != '') {
				fseek($f, min($offset, $stat['size']) - 1, SEEK_SET);
				fgets($f); // read '\n' if we are on begin of line, otherwise read to begin of next line
				$real_offset = ftell($f);
				if ($real_offset != $offset) {
					debug_msg('Snap to byte %s (requested %s).', $real_offset, $offset);
					$url = filename_format($snap_url, array('name' => $log_cfg['name'], 'offset' => $real_offset));
					$this->templateOptionSet('root', 'redirect_url', $url);
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
			'prev' => $this->findPrevPageOffset($f, $begin_offset, $max_lines),
			'eof' => $stat['size'],
		);

		$this->out('lines', $lines);
		$this->out('offsets', $offsets);
		$this->out('count', $i);
		$this->out('done', true);

		fclose($f);
	}

	private function findPrevPageOffset($f, $begin_offset, $max_lines, $step = 4096)
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


