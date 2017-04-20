<?php



class uploadHandler{

	protected $tmp_dir = TMP_DIR; //see config file

	// PHP File Upload error message codes:
    // http://php.net/manual/en/features.file-upload.errors.php    
	protected $error_messages = array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'max_file_size' => 'File is too big',
        'min_file_size' => 'File is too small',
        'accept_file_types' => 'Filetype not allowed',
        'max_number_of_files' => 'Maximum number of files exceeded',
        'max_width' => 'Image exceeds maximum width',
        'min_width' => 'Image requires a minimum width',
        'max_height' => 'Image exceeds maximum height',
        'min_height' => 'Image requires a minimum height',
        'abort' => 'File upload aborted',
        'image_resize' => 'Failed to resize image'
    );

 	public function __construct($files){
 		$this->database = new Database();
 		$upload = $files["files"];
 		$content_disposition_header = @$_SERVER['HTTP_CONTENT_DISPOSITION'];
		$file_name = $content_disposition_header ?
		    rawurldecode(preg_replace(
		        '/(^[^"]+")|("$)/',
		        '',
		        $content_disposition_header
		    )) : null;

		// Parse the Content-Range header, which has the following form:
		// Content-Range: bytes 0-524287/2000000
		$content_range_header = @$_SERVER['HTTP_CONTENT_RANGE'];
		echo $content_range_header;
		$content_range = $content_range_header ?
		    preg_split('/[^0-9]+/', $content_range_header) : null;
		$size =  $content_range ? $content_range[3] : $files["files"]["size"]; // size of the complete file	
		if($upload){
			$this->handle_file_upload($upload,$size);			
		}
 	}

 	protected function handle_file_upload($upl, $size){

		$fPathInfo = pathinfo($upl['name']);
			
		$file = new \stdClass();
		$file->name = (isset($fPathInfo["filename"])) ? $fPathInfo["filename"] : "noname";
	    $file->extension = strtolower((isset($fPathInfo["extension"])) ? $fPathInfo["extension"] : "");	        
		$file->size = $size; // size of the uploaded file
		$file->type = $upl["type"]; // type of the uploaded file
		$file->path = $this->tmp_dir.$upl["name"]; // path to the uploaded file in temporary dir
		$uploaded_chunk = $upl["tmp_name"]; // path of the chunk 
		$error = $upl["error"];

		// Check if error while uploading file
		if ($error) {
	        $file->error = $this->get_error_message($error);
	        die($file->error);
	    }
	    // Create temporary directory
	    if (!is_dir($this->tmp_dir)) {
	        mkdir($this->tmp_dir, 0755, true);
	    }
	    
	    if(is_uploaded_file($uploaded_chunk)){

	    	$chunk_content = fopen($uploaded_chunk, 'r');
	    	file_put_contents($file->path,$chunk_content,FILE_APPEND);
	    	if(filesize($file->path) == $size){
	    		$udata = $this->database->getUserById($_SESSION["id"]);
	    		if($udata["u_size_used"]+$size <= $udata["u_size_total"]){ // Userhas enougth space
	    			// File
					$ffilename = $this->getRandomName().".".$file->extension;
					$fid = $this->database->insertFile($file->name,$file->extension,mime_content_type($file->path),$file->size,md5_file($file->path),$ffilename);
				
					// Storage :
					$this->database->updateUserDiskUsage($file->size);				
					$storages = $this->database->getBestStorages($file->size);
					if(count($storages) == 2){ // Find 2 storage to hold the file
						foreach ($storages as $storage) {
							$this->database->storeFile($fid, $storage["s_id"]);
							$this->database->updateStorageUsage( $storage["s_id"], $file->size );
							copy($file->path,$storage["s_path"].$ffilename);
						}
						unlink($file->path);
						
						echo "success";
					}
					else { // No storage found to hold the file
						unlink($file->path);
						echo "error - no more storage";
					}
	       		}
	       		else { // User not enougth space
	       			unlink($file->path); // remove file from temporary dir
	       			echo "error - not enought space";
	       		}
	       	}
	    	else if(filesize($file->path) > $size) {
	    		unlink($file->path);
	    		echo "error - something went wrong";
	    	}
	    		   	
	    }
	}

	private function getRandomName($prefix="", $len=12){
	    $characters ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $string = $prefix.'';    
	    for($i=0;$i<$len;$i++){
	        $string .= $characters[rand(0,strlen($characters)-1)];
	    }
	    return $string;
	}

 	protected function get_error_message($error) {
        return isset($this->error_messages[$error]) ? $this->error_messages[$error] : $error;
    }
}
?>