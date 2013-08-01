<?php
class lpr {
	private $data = array(
		'paper_size' => 'A4',
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
		} else $this->files = array($files);

		foreach($this->files as $file) {
			if(!file_exists($file)) {
				throw new Exception('Der er en fil som blev brugt som argument til '.__CLASS__.' som ikke findes. File er: '.$file);
			}
		}
	}

	public function exec() {
		$args = array(
			'-o media='.escapeshellarg($this->data['paper_size']),
			'-# '.escapeshellarg($this->data['copies']),
			'-T "Job '.mt_rand().'"',
		);
		
		if($this->data['encryption'])
			$args[] = '-E';

		if(!is_null($this->data['hostname']))
			$args[] = '-H '.escapeshellarg($this->data['hostname'].(!is_null($this->data['port']) ? ':'.$this->data['port'] : ''));

		if(!is_null($this->data['username']))
			$args[] = '-U '.escapeshellarg($this->data['username']);

		$output = array();
		$error_file = sys_get_temp_dir().DIRECTORY_SEPARATOR.mt_rand().'.lpr';
		exec('lpr '.implode(' ', $args).' '.implode(' ', $this->files).'  2> '.$error_file, $output, $return_var);

		$error = trim(file_get_contents($error_file));

		@unlink($error_file);

		if($return_var > 0)
			throw new Exception($error);

		return true;
	}

	public function __set($x, $y) {
		if(isset($this->data[$x])) {
			$this->data[$x] = $y;
			return true;
		}
		return false;
	}

	public function __get($x) {
		return (isset($this->data[$x]) ? $this->data[$x] : null);
	}
}