<?php
class Lpr {
	private $data = array(
		'paper_size' => 'A4',
		'tray' => 'Tray3',
		'username' => null,
		'hostname' => null,
		'port' => null,
		'copies' => '1',
		'encryption' => false,
	);
	private $files = array();

	public function __construct($files) {
		if(is_array($files)) {
			$this->files = $files;
		}
		else {
			$this->files = array($files);
		}

		foreach($this->files as $file) {
			if(!file_exists($file)) {
				throw new Exception('Der er en fil som blev brugt som argument til '.__CLASS__.' som ikke findes. File er: '.$file);
			}
		}
	}

	public function exec() {
		$args = array(
			'-o media='.escapeshellarg($this->data['paper_size'].','.$this->data['tray']),
			'-# '.escapeshellarg($this->data['copies']),
			'-T "Job '.mt_rand().'"',
		);
		
		if($this->data['encryption'])
			$args[] = '-E';

		if(!is_null($this->data['hostname']))
			$args[] = '-H '.escapeshellarg($this->data['hostname'].(!is_null($this->data['port']) ? ':'.$this->data['port'] : ''));

		if(!is_null($this->data['username']))
			$args[] = '-U '.escapeshellarg($this->data['username']);

		$files = array();
		foreach($this->files as $file) {
			$files[] = escapeshellarg($file);
		}

		$output = array();
		$error_file = sys_get_temp_dir().DIRECTORY_SEPARATOR.mt_rand().'.lpr';
		exec($cmd = 'lpr '.implode(' ', $args).' '.implode(' ', $files).'  2> '.$error_file, $output, $return_var);
		
		syslog(E_NOTICE, 'CMD: '.$cmd);

		$error = trim(file_get_contents($error_file));

		@unlink($error_file);

		if($return_var > 0)
			throw new Exception($error);

		return true;
	}

	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

	public function __get($name) {
		return (isset($this->data[$name]) ? $this->data[$name] : null);
	}
}