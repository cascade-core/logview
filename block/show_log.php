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

class B_logview__show_log extends \Cascade\Core\Block
{

	protected $inputs = array(
		'log_cfg' => false,		// Log configuration (simple text by default)
		'lines' => array(),
		'line_link' => '#byte{offset}',
		'slot' => 'default',
		'slot_weight' => 50,
	);

	protected $outputs = array(
		'done' => true,
	);

	const force_exec = true;


	public function main()
	{
		$log_cfg = $this->in('log_cfg');
		$lines = $this->in('lines');

		if ($log_cfg === false) {
			// Default configuration
			$log_cfg = array(
				'name' => null,
				'view' => 'log',
				'line_link' => null,
			);
		}

		switch (@$log_cfg['view']) {

			case 'plot':
				// Convert data if convertor specified (invocable object)
				$convertor = @ $log_cfg['plot_data_convertor'];
				if ($convertor && class_exists($convertor)) {
					$c = new $convertor();
					$data = $c($lines);
				} else {
					$data = $lines;
				}
				// Add plot to output (javascript will draw plot at client)
				$this->templateAdd('plot', 'logview/plot', array(
						'name' => $log_cfg['name'],
						'plot_type' => $log_cfg['plot_type'],
						'data' => $data,
					));
				break;

			default:
			case 'log':
				// Simple log
				$this->templateAdd(null, 'logview/log', array(
						'name' => $log_cfg['name'],
						'lines' => $lines,
						'line_link' => $this->in('line_link'),
					));
				break;

		}


		$this->out('done', true);
	}

}


