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

class B_logview__load_config extends \Cascade\Core\Block
{
	protected $inputs = array(
		'name' => false,
	);

	protected $outputs = array(
		'all' => true,
		'selected' => true,
		'done' => true,
	);

	const force_exec = false;


	public function main()
	{
		$cfg = parse_ini_file('logs.ini.php', TRUE);

		if ($cfg === FALSE) {
			// FIXME: NEVER DO THIS !!!  --  It is very bad example.
			header('Content-Type: text/plain');
			echo "Log configuration in logs.ini.php is missing.\n\nExample:\n\n";
			echo ";<?", "php exit(); __halt_compiler(); ?", ">\n";
			echo ";\n";
			echo "; Log filenames\n";
			echo ";\n";
			echo "\n";
			echo "[apache]\n";
			echo "file = \"/var/log/apache2/error.log\"\n";
			echo "description = \"Apache web server error log as found on Debian.\"\n";
			echo "\n";
			echo "[httpd]\n";
			echo "file = \"/var/log/httpd-error.log\"\n";
			echo "description = \"Apache web server error log as found on FreeBSD.\"\n";
			echo "\n";
			echo "[lighttpd]\n";
			echo "file = \"/var/log/lighttpd/error.log\"\n";
			echo "description = \"Lighttpd web server error log.\"\n";
			echo "\n";
			echo "[syslog]\n";
			echo "file = \"/var/log/syslog\"\n";
			echo "description = \"System log. You should not want to show this log here.\"\n";
			echo "\n";
			echo "\n";
			echo "; vim:filetype=dosini:\n";
			exit();
		}

		$name = $this->in('name');
		if ($name !== false) {
			if (!array_key_exists($name, $cfg)) {
				error_msg('Requested log configuration not found: %s', $name);
				return;
			}
			$selected = $cfg[$name];
			$selected['name'] = $name;
			$this->out('selected', $selected);
		}

		$this->out('all', $cfg);
		$this->out('done', true);
	}
}



