<?php  

/**
 * Zip Compression Class
 *
 *	Based on Code Igniter ZIP Library 
 * 	http://codeigniter.com/user_guide/libraries/zip.html
 */
class DPLS_Zip  {

	var $zipdata 	= '';
	var $directory 	= '';
	var $entries 	= 0;
	var $file_num 	= 0;
	var $offset		= 0;

	function DPLS_Zip()
	{
		//log_message('debug', "Zip Compression Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Add Directory
	 *
	 * Lets you add a virtual directory into which you can place files.
	 *
	 * @access	public
	 * @param	mixed	the directory name. Can be string or array
	 * @return	void
	 */
	function add_dir($directory)
	{
		foreach ((array)$directory as $dir)
		{
			if ( ! preg_match("|.+/$|", $dir))
			{
				$dir .= '/';
			}

			$this->_add_dir($dir);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Add Directory
	 *
	 * @access	private
	 * @param	string	the directory name
	 * @return	void
	 */
	function _add_dir($dir)
	{
		$dir = str_replace("\\", "/", $dir);

		$this->zipdata .=
			"\x50\x4b\x03\x04\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00"
			.pack('V', 0) // crc32
			.pack('V', 0) // compressed filesize
			.pack('V', 0) // uncompressed filesize
			.pack('v', strlen($dir)) // length of pathname
			.pack('v', 0) // extra field length
			.$dir
			// below is "data descriptor" segment
			.pack('V', 0) // crc32
			.pack('V', 0) // compressed filesize
			.pack('V', 0); // uncompressed filesize

		$this->directory .=
			"\x50\x4b\x01\x02\x00\x00\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00"
			.pack('V',0) // crc32
			.pack('V',0) // compressed filesize
			.pack('V',0) // uncompressed filesize
			.pack('v', strlen($dir)) // length of pathname
			.pack('v', 0) // extra field length
			.pack('v', 0) // file comment length
			.pack('v', 0) // disk number start
			.pack('v', 0) // internal file attributes
			.pack('V', 16) // external file attributes - 'directory' bit set
			.pack('V', $this->offset) // relative offset of local header
			.$dir;

		$this->offset = strlen($this->zipdata);
		$this->entries++;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Add Data to Zip
	 *
	 * Lets you add files to the archive. If the path is included
	 * in the filename it will be placed within a directory.  Make
	 * sure you use add_dir() first to create the folder.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */	
	function add_data($filepath, $data = NULL)
	{
		if (is_array($filepath))
		{
			foreach ($filepath as $path => $data)
			{
				$this->_add_data($path, $data);
			}
		}
		else
		{
			$this->_add_data($filepath, $data);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Add Data to Zip
	 *
	 * @access	private
	 * @param	string	the file name/path
	 * @param	string	the data to be encoded
	 * @return	void
	 */	
	function _add_data($filepath, $data)
	{
		$filepath = str_replace("\\", "/", $filepath);

		$uncompressed_size = strlen($data);
		$crc32  = crc32($data);

		$gzdata = gzcompress($data);
		$gzdata = substr($gzdata, 2, -4);
		$compressed_size = strlen($gzdata);

		$this->zipdata .=
			"\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00\x00\x00\x00\x00"
			.pack('V', $crc32)
			.pack('V', $compressed_size)
			.pack('V', $uncompressed_size)
			.pack('v', strlen($filepath)) // length of filename
			.pack('v', 0) // extra field length
			.$filepath
			.$gzdata; // "file data" segment

		$this->directory .=
			"\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00\x00\x00\x00\x00"
			.pack('V', $crc32)
			.pack('V', $compressed_size)
			.pack('V', $uncompressed_size)
			.pack('v', strlen($filepath)) // length of filename
			.pack('v', 0) // extra field length
			.pack('v', 0) // file comment length
			.pack('v', 0) // disk number start
			.pack('v', 0) // internal file attributes
			.pack('V', 32) // external file attributes - 'archive' bit set
			.pack('V', $this->offset) // relative offset of local header
			.$filepath;

		$this->offset = strlen($this->zipdata);
		$this->entries++;
		$this->file_num++;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Read the contents of a file and add it to the zip
	 *
	 * @access	public
	 * @return	bool
	 */	
	function read_file($path, $preserve_filepath = FALSE)
	{
		if ( ! file_exists($path))
		{
			return FALSE;
		}

		if (FALSE !== ($data = file_get_contents($path)))
		{
			$name = str_replace("\\", "/", $path);
			
			if ($preserve_filepath === FALSE)
			{
				$name = preg_replace("|.*/(.+)|", "\\1", $name);
			}

			$this->add_data($name, $data);
			return TRUE;
		}
		return FALSE;
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Read a directory and add it to the zip.
	 *
	 * This function recursively reads a folder and everything it contains (including
	 * sub-folders) and creates a zip based on it.  Whatever directory structure
	 * is in the original file path will be recreated in the zip file.
	 *
	 * @access	public
	 * @param	string	path to source
	 * @return	bool
	 */	
	function read_dir($path)
	{	
		
		
		
		if ($fp = @opendir($path))
		{
			while (FALSE !== ($file = readdir($fp)))
			{
				if (@is_dir($path.$file) && substr($file, 0, 1) != '.')
				{					
					$this->read_dir($path.$file."/");
				}
				elseif (substr($file, 0, 1) != ".")
				{
					if (FALSE !== ($data = file_get_contents($path.$file)))
					{						
						$this->add_data(str_replace("\\", "/", $path).$file, $data);
					}
				}
			}
			return TRUE;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Get the Zip file
	 *
	 * @access	public
	 * @return	binary string
	 */	
	function get_zip()
	{
		// Is there any data to return?
		if ($this->entries == 0)
		{
			return FALSE;
		}

		$zip_data = $this->zipdata;
		$zip_data .= $this->directory."\x50\x4b\x05\x06\x00\x00\x00\x00";
		$zip_data .= pack('v', $this->entries); // total # of entries "on this disk"
		$zip_data .= pack('v', $this->entries); // total # of entries overall
		$zip_data .= pack('V', strlen($this->directory)); // size of central dir
		$zip_data .= pack('V', strlen($this->zipdata)); // offset to start of central dir
		$zip_data .= "\x00\x00"; // .zip file comment length

		return $zip_data;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Write File to the specified directory
	 *
	 * Lets you write a file
	 *
	 * @access	public
	 * @param	string	the file name
	 * @return	bool
	 */	
	function archive($filepath)
	{
		if ( ! ($fp = @fopen($filepath, FOPEN_WRITE_CREATE_DESTRUCTIVE)))
		{
			return FALSE;
		}

		flock($fp, LOCK_EX);	
		fwrite($fp, $this->get_zip());
		flock($fp, LOCK_UN);
		fclose($fp);

		return TRUE;	
	}

	// --------------------------------------------------------------------

	/**
	 * Download
	 *
	 * @access	public
	 * @param	string	the file name
	 * @param	string	the data to be encoded
	 * @return	bool
	 */
	function download($filename = 'backup.zip')
	{
		if ( ! preg_match("|.+?\.zip$|", $filename))
		{
			$filename .= '.zip';
		}

		$zip_content =& $this->get_zip();

	
		
		$this->force_download($filename, $zip_content);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize Data
	 *
	 * Lets you clear current zip data.  Useful if you need to create
	 * multiple zips with different data.
	 *
	 * @access	public
	 * @return	void
	 */		
	function clear_data()
	{
		$this->zipdata		= '';
		$this->directory	= '';
		$this->entries		= 0;
		$this->file_num		= 0;
		$this->offset		= 0;
	}
	
	
	function force_download($filename = '', $data = ''){
			if ($filename == '' OR $data == '')
			{
				return FALSE;
			}
			
			// Try to determine if the filename includes a file extension.
			// We need it in order to set the MIME type
			if (FALSE === strpos($filename, '.'))
			{
				return FALSE;
			}

			
			// Grab the file extension
			$x = explode('.', $filename);
			$extension = end($x);

			// Load the mime types
			@include(MAIN_PATH.'/lib/helper/mimes.php');

			// Set a default mime if we can't find it
			if ( ! isset($mimes[$extension]))
			{
				$mime = 'application/octet-stream';
			}
			else
			{
				$mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
			}

			// Generate the server headers
			if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
			{
				header('Content-Type: "'.$mime.'"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header("Content-Transfer-Encoding: binary");
				header('Pragma: public');
				header("Content-Length: ".strlen($data));
			}
			else
			{
				header('Content-Type: "'.$mime.'"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header("Content-Transfer-Encoding: binary");
				header('Expires: 0');
				header('Pragma: no-cache');
				header("Content-Length: ".strlen($data));
			}

			exit($data);
		}

	
	
}
