<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*

  Author		Ulises Rodríguez
  Site:			http://www.ulisesrodriguez.com	
  Twitter:		https://twitter.com/#!/isc_ulises
  Facebook:		http://www.facebook.com/ISC.Ulises
  Github:		https://github.com/ulisesrodriguez
  Email:		ing.ulisesrodriguez@gmail.com
  Skype:		systemonlinesoftware
  Location:		Guadalajara Jalisco Mexíco

  required library php-excel-reader;	
*/
class Reader_excel{
	
	private $reader = null;	

	private $file = null;

	private $directory;
			
    public function __construct()
    {
		error_reporting(E_ALL ^ E_NOTICE);
		
		$this->directory = APPPATH.'modules/ot/assets/tmp/';
		
		@require 'php-excel-reader/excel_reader2.php' ;
         				
    }
	
	
// Setting intance
	public function setInstance( $file = null ){
		
		if( empty( $file ) ) return false;
		
		if( !is_file( $this->directory . $file ) ) return false;
		
		$this->file = $file;
		
		$this->reader =  new Spreadsheet_Excel_Reader( $this->directory . $file ); 
		
	}
	
	
// Reader and setting table format	
	public function reader_dump(){
		
		if( !is_object( $this->reader ) ) return false;	
		
		echo $this->reader->dump(true,true);
	}
	
	
	
// 	Reader for row and col data
	public function reader($sheet = 0){
		
		if( !is_object( $this->reader ) ) return false;	
		
		$data = array();	
			
		for($row=2; $row<=$this->reader->rowcount($sheet); $row++)
			
			for($col=1; $col<=$this->reader->colcount($sheet); $col++)
								
				$data[$row][$col] = $this->reader->val( $row, $col, $sheet ) ;
								
		
		return $data;
	}


// Upload Files
	public function upload(){
				
		if( !isset( $_FILES['file'] ) or empty( $_FILES  ) ) return false;
		
		if( $_FILES['file']['type'] != 'application/vnd.ms-excel' ) return false;	
			
		// Setting file temporal file named			
		$name = $_FILES['file']['name'];
		
		$name = strtr($name, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');

		$name = preg_replace('/([^.a-z0-9]+)/i', '_', $name);

		if( is_dir( $this->directory ) )
				
				if( !move_uploaded_file( $_FILES['file']['tmp_name'],  $this->directory  . $name ) ) return false;
		
		return $name;
		
	}


// Erases Files	
	public function drop(){
		
		if( empty( $this->file ) ) return false;
						
		if( is_file( $this->directory . $this->file ) )
			
			unlink( $this->directory . $this->file );
		
		$this->reader = null;
		
		$this->file = null;
					
		return true;
					
	}
}
?>