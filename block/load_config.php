<?php
/*
 * Copyright (c) 2011, Josef Kufner  <jk@frozen-doe.net>
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

class B_logview__load_config extends Block
{
	protected $inputs = array(
	);

	protected $outputs = array(
		'file_map' => true,
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
			echo "[file_map]\n";
			echo "apache = \"/var/log/apache2/error.log\"\n";
			echo "httpd = \"/var/log/httpd-error.log\"\n";
			echo "lighttpd = \"/var/log/lighttpd/error.log\"\n";
			echo "syslog = \"/var/log/syslog\"\n";
			echo "\n";
			echo "\n";
			echo "; vim:filetype=dosini:\n";
			exit();
		}

		$this->out_all($cfg);
		$this->out('done', true);
	}
}



