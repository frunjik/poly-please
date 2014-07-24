<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('CR', "\r");          // Carriage Return: Mac
define('LF', "\n");          // Line Feed: Unix
define('CRLF', "\r\n");      // Carriage Return and Line Feed: Windows
define('BR', '<br />' . LF); // HTML Break	

class Please extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->name = 'please';
		
		// TODO: put in config
		$this->path_logs = 'C:/wamp/logs/';										// ends with SLASH
		$this->path_root = 'C:/Projects/Web/poly-please/server/cigly/';			// ends with SLASH
		
		// TODO: put in dict - generalize ...
		$this->page_view = 'poly_page_view';
		$this->page_edit = 'poly_page_edit';
		$this->page_work = 'poly_page_work';
	}

	public function index($dummy='')
	{
		$this->load->helper('url');
		redirect('/please/view/');
	}

	public function view($dummy='')
	{
		$this->load->helper('url');
		$result = '';
		$content = $this->uri->uri_string();
		if($dummy=='')
			$content = $content.'/poly_view_welcome';

		$page = $this->load->view($this->page_view, '', true);
		$content = str_replace($this->name.'/view', '', $content);
		
		if($content)
		{
			if($content[0]==='/') $content=substr($content,1);
			$page = str_replace('[get_url]', site_url().$this->name.'/view/'.$content, $page);
			$result = $content;			
			$result = $this->do_load($content,$result);
			$result = $this->replace_content($result, $page);
		}
		else
			$page = str_replace('[get_url]', site_url().$this->name.'/view/', $page);

		if(!$result)
			$result = $page;
		echo $result;
	}
	
	private function __view($url)
	{
		$this->load->helper('url');
		$result = '[content]';
		$content = $url;
		$page = $this->load->view($this->page_view, '', true);
		$content = str_replace($this->name.'/view', '', $content);
		if($content)
		{
			if($content[0]==='/') $content=substr($content,1);
			$page = str_replace('[get_url]', site_url().$this->name.'/view/'.$content, $page);
			$result = $content;			
			$result = $this->do_load($content,$result);
			$result = $this->replace_content($result, $page);
		}
		else
			$page = str_replace('[get_url]', site_url().$this->name.'/view/', $page);

		if(!$result)
			$result = $page;
		return $result;
	}
	
	public function edit($dummy='')
	{
		$this->load->helper('url');
	
		$result = '[content]';
		$content = $this->uri->uri_string();
		$content = str_replace($this->name.'/edit/', '', $content);
		$page = $this->load->view($this->page_edit, '', true);

		if($content != $this->name.'/edit' && $content != $this->name.'/edit/')
		{
			$page = str_replace('[get_url]', site_url().$this->name.'/load/'.$content, $page);
			
			// TODO test if view exists ????
			$result = $this->do_load($content,$result);
			
			if($result)
				$result = html_escape($result);

			$result = $this->replace_content($result, $page);
		}
		else
			$result = $page;
		echo $result;
	}

	public function work($dummy='')
	{
		$this->load->helper('url');
	
		$result = '[content]';
		$content = $this->uri->uri_string();
		$content = str_replace($this->name.'/work/', '', $content);
		$page = $this->load->view($this->page_work, '', true);
		if($content)
		{
			$page = str_replace('[get_url]', site_url().$this->name.'/load/'.$content, $page);

			$result = $this->do_load($content,$result);
			if($result)
				$result = html_escape($result);
			$result = $this->replace_content($result, $page);
		}
		else
			$result = $page;
		echo $result;
	}

	public function load($dummy='')
	{
		$result = '';
		$content = $this->uri->uri_string();
		$content = str_replace($this->name.'/load/', '', $content);
		if($content)
		{
			$result = $this->do_load($content,$result);
		}
		echo $result;
	}
	
	public function save($dummy='')
	{
		$this->load->helper('url');
		$this->load->helper('file');

		$uri = $this->uri->uri_string();
		$file = str_replace($this->name.'/save/', '', $uri);
		
		//echo $this->path_root;
		//return;

		$path = $this->path_root.'views/'.$file.'.php';

		$goto = $this->input->get('goto');
		if($goto)
		{
		}
		else
		{
			$page = $this->input->get('page');
			if($page)
				$goto = site_url().str_replace($this->name.'/save', $this->name.'/'.$page, $uri);
			else
				$goto = site_url().str_replace($this->name.'/save', $this->name.'/edit', $uri);
		}
		
		$post = $this->input->post();
		$data = $post['content'];

		//echo $path.'<br/>';
		//if($goto)
		//	echo $goto.'<br/>';
		//echo(html_escape($data));
		//return;
		
		write_file($path, $data, 'w+');
		redirect($goto);
	}

	public function get($subj='files',$with='')
	{
		if($subj=='files')
		{
			$path = $this->path_root.'views/';
			$files = scandir($path);
			$result = array();
			foreach($files as $f)
			{
				if(
					substr($f, -strlen('.php')) === '.php' &&
					!$with || substr($f, 0, strlen($with)) === $with
					)		
				{
					array_push($result, str_replace('.php','',$f));
				}
			}
			echo json_encode($result);
		}
		else if($subj=='access')
		{
			echo $this->tail_br($this->path_logs.'access.log', $with);
		}
		else if($subj=='server')
		{
			echo $this->tail_br($this->path_logs.'apache_error.log', $with);
		}
		else if($subj=='errors')
		{
			echo $this->tail_br($this->path_logs.'php_error.log', $with);
		}
		else
		{
			echo json_encode(array('Wut? - you can get [files | access | server | errors]'));
		}
	}

	function tail_br($filepath, $lines = 1, $adaptive = true) {
		if(is_numeric($lines))
			$lines = intval($lines);
		if($lines<1)
			$lines = 25;
		$chunck = $this->tailCustom($filepath, $lines);
		$chunck = explode("\n", $chunck);
		$chunck = implode('<br/>', $chunck);
		return $chunck;
	}
	
	function tailCustom($filepath, $lines = 1, $adaptive = true) {
 
		// Open file
		$f = @fopen($filepath, "rb");
		if ($f === false) return false;
 
		// Sets buffer size
		if (!$adaptive) $buffer = 4096;
		else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
 
		// Jump to last character
		fseek($f, -1, SEEK_END);
 
		// Read it and adjust line number if necessary
		// (Otherwise the result would be wrong if file doesn't end with a blank line)
		if (fread($f, 1) != "\n") $lines -= 1;
		
		// Start reading
		$output = '';
		$chunk = '';
 
		// While we would like more
		while (ftell($f) > 0 && $lines >= 0) {
 
			// Figure out how far back we should jump
			$seek = min(ftell($f), $buffer);
 
			// Do the jump (backwards, relative to where we are)
			fseek($f, -$seek, SEEK_CUR);
 
			// Read a chunk and prepend it to our output
			$output = ($chunk = fread($f, $seek)) . $output;
 
			// Jump back to where we started reading
			fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
 
			// Decrease our line counter
			$lines -= substr_count($chunk, "\n");
 
		}
 
		// While we have too many lines
		// (Because of buffer size we might have read too many)
		while ($lines++ < 0) {
 
			// Find first newline and remove all text before that
			$output = substr($output, strpos($output, "\n") + 1);
 
		}
 
		// Close file and return
		fclose($f);
		return trim($output);
 	}	
	
	
	
	public function go($dummy='')
	{
		$cmds = $this->uri->segments;
		array_shift($cmds);				// [controller]
		array_shift($cmds);				// go
		echo $this->__go($cmds, '');
	}

	public function debug($dummy='')
	{
		$cmds = $this->uri->segments;
		array_shift($cmds);				// [controller]
		array_shift($cmds);				// debug

		$result = '';
		while($cmds)
		{
			$cmd = array_pop($cmds);
			
			echo 'testing: '.$cmd.' ==> ';
			
			if($this->is_literal($cmd))
			{
				echo 'do_literal ==> ';
				$result = $this->do_literal(substr($cmd,1),$result);
			}
			else if($this->is_html($cmd))
			{
				echo 'do_html ==> ';
				$result = $this->do_html(substr($cmd,1),$result);
			}
			else if($this->has_method('_'.$cmd))
			{
				$method = '_'.$cmd;
				echo 'do_method('.$method.') ==> ';
				$result = $this->do_call($method,$result);
			}
			else
			{
				echo 'do_load('.$cmd.') ==> ';
				$result = $this->do_load($cmd,$result);
			}

			echo $this->_escape($result);
			echo '<hr/>';
		}
		
		echo '<hr/>';
		echo '==><br/>';
		echo $this->_escape($result);
		echo '<hr/>';
	}

	private function __go($cmds,$content)
	{
		$result = $content;
		while($cmds)
		{
			$cmd = array_pop($cmds);
			if($this->is_literal($cmd))
			{
				$result = $this->do_literal(substr($cmd,1),$result);
			}
			else if($this->is_html($cmd))
			{
				$result = $this->do_html(substr($cmd,1),$result);
			}
			else if($this->has_method('_'.$cmd))
			{
				$method = '_'.$cmd;
				$result = $this->do_call($method,$result);
			}
			else
			{
				$result = $this->do_load($cmd,$result);
			}
		}
		return $result;
	}

	private function is_literal($cmd)
	{
		return $cmd[0] == '-';
	}
	
	private function do_literal($lit,$content)
	{
		if(!$content)
		{
			$result = $lit;
		}
		else
		{
			$result = $this->replace_content($lit, $content);
		}
		return $result;
	}
	
	private function is_html($cmd)
	{
		return $cmd[0] == ':';
	}
	
	private function do_html($tag, $content)
	{
		if(!$content)
		{
			$result = '<'.$tag.'/>';
		}
		else
		{
			$result = '<'.$tag.'>'.$content.'</'.$tag.'>';
		}
		return $result;
	}

	private function has_method($method)
	{
		return method_exists($this, $method);
	}
	
	private function do_call($method,$content)
	{
		return $this->$method($content);
	}
	
	private function do_load($view,$content)
	{
		return $this->replace_content($content, $this->load->view($view,'',true));
	}
	
	private function to_dos($string) {
		return $this->chg_line_end(String, '\r\r');
	}

	private function chg_line_end($string, $use_ending) {
		/***
		(?<=     - Start of a lookaround (behind)
		  [^\r]  - Match any character that is not a Carriage Return (\r)
		  |      - OR
		  ^      - Match the beginning of the string (in order to capture newlines at the start of a string
		)        - End of the lookaround
		\n       - Match a literal LineFeed (\n) character		
		***/
		return preg_replace("/(?<=[^\r]|^)\n/", $use_ending, $string);
	}

	private function replace_content($with, $haystack)
	{
		return str_replace('[content]', $with, $haystack);
	}
	
	// test methods - callable via go
	private function _foo($content='')
	{
		return 'its foo';
	}

	private function _hello($content='World')
	{
		return 'hello to "'.$content.'" from poly-please';
	}

	private function _escape($content='')
	{
		return html_escape($content);
	}
	
	private function _echo($content='')
	{
		return $content;
	}


	
	
	
}
		

/* End of file please.php */
/* Location: ./application/controllers/please.php */