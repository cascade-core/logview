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

class B_logview__show_navigation extends \Cascade\Core\Block
{

	protected $inputs = array(
		'log_cfg' => array(),		// Log configuration
		'offsets' => array(),
		'link' => array(),
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
		$offsets = $this->in('offsets');

		$this->templateAdd(null, 'logview/navigation', array(
				'file' => $log_cfg['file'],
				'name' => $log_cfg['name'],
				'link' => $this->in('link'),
				'begin_offset' => $offsets['begin'],
				'prev_offset' => $offsets['prev'],
				'end_offset' => $offsets['end'],
				'eof_offset' => $offsets['eof'],
				'at_begin' => $offsets['begin'] == 0,
				'at_eof' => $offsets['end'] == $offsets['eof'],
			));

		$this->out('done', true);
	}

}


