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

class B_logview__show_menu extends \Cascade\Core\Block
{

	protected $inputs = array(
		'all_log_cfg' => array(),		// Full log configuration
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
		$this->templateAdd(null, 'logview/menu', array(
				'all_log_cfg' => $this->in('all_log_cfg'),
				'link' => $this->in('link'),
			));

		$this->out('done', true);
	}

}


