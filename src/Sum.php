<?php

/**
 * Class Sum
 *
 * @param  array $result The result holder 

 * An object class that calculates the sum of numbers in a file having
 * other file names inclusive with nums in each of them
 */
class Sum{
	public $response;
	public $fileHandler;
	public $currentFile;
	
	function __construct(){
       
          $this->response =  [
		    "keys"=>[],
		    "values"=>[], 
		    "final"=>[]
		  ];
       
	}

     /**
	 * Function getSumation
	 *
	 * @param  string $filename The starting file to iterate over
	 * @param  array $result The result holder 

	 * Usage and description
	 *  $startFile = "A.txt";
	 *  
	 *  print_r( (new Summation\Sum())->getSumation($startFile));
	 * A function that calculates the sum of numbers in a file having
	 * other file names inclusive with nums in each of them
	 */

	function getSumation(string $filename, array $bufferStack=[]): array
	{
	  
	  /*bail if file does not exist
	   * basically fails safe if the input data is not a file of file
	   * relative path does not exist
	  */

	  if(!file_exists($filename)){
	    throw new \InvalidArgumentException("File does not exist");
	  }
	  $this->currentFile = $filename;
	  // $filename = basename(realpath(dirname(__FILE__).'/'.$filename));  
	  // $ext = strtolower(pathinfo($filename,   PATHINFO_EXTENSION));
	  $bufferStack = []; //holds the sequence of file and numbers extraction
	  $this->fileHandler = file($filename,FILE_SKIP_EMPTY_LINES); // read the file
	  $bufferStack[] = $filename; // for key pair ordering key==filename, val == array(n) 0<=n<=infinity  
	  $i =0;
	  /*Read through each lines 
	   * Run recursive function if a .txt file is found in the line
	   * if found  a .txt file, rerun iteration and file extraction
	  */
	  foreach( $this->fileHandler  as $key => $inputFeed )
	  {
	     /*simple check if we find a . (dot) extention to denote our feed line is a file encounter*/    
	     $last_period = strrpos($inputFeed, '.');
	     if($last_period !== false){
	        //recursion reiteration reloop  into the newly found file
	        // and run extraction into $orderings
	        array_push($bufferStack, $this->getSumation(trim($inputFeed),$bufferStack) );
	     }else{
	       /*if we find a number then just append to orderings*/
	        array_push($bufferStack , $inputFeed);
	     }        
	  }  

	  //summation of numbers found in each files
	  $total_sumation =  array_sum($bufferStack) ;
	  $bufferStack["sum"] = $total_sumation;

	  $i = 0;
	  // sort individual key pairs from file extracted orderings
	  foreach($bufferStack as $pairKey =>$val){
	    // filter values
	    if (!is_numeric($pairKey) && !is_array($val)) {
	      array_push($this->response['values'], $val);
	    }else if( !is_array($val) && preg_match_all('/[.a-zA-Z0-9]+\.txt/', $val, $matches)){
	      //filter keys eg $response["A.txt"] = sum of all numbers in file excluding filenames found in file A.txt
	       array_push($this->response['keys'], $val);
	    }
	  }
	  
	  //total summation resolution per index iter
	  $result = $this->resolveSummation($this->response);
	  return $result;
	 
	}

	private function resolveSummation(): array{
     // run logic conditions
     for( 
        $i=count($this->response["values"]),
        $j =count($this->response["values"])-1; $i>0,
        $j>-1; 
        $i--, $j--
    ){
      // on each loop slice out certain number of values from the response set
       $resolutionSum = array_slice( $this->response["values"],0, $i);
       $summ = array_sum($resolutionSum);
      // arrange key pair of resulting responses
       //eg : $response["A.txt"] = sum of all numbers in file including filenames found in file A.txt and other files recursively linked to A.txt by sub files in its file content stream
       $this->response["final"][$this->response["keys"][$j]] =array_sum($resolutionSum);

    }
    //   $response["final"] = array_unique($response["final"]);
    //shiny bits response
    return $this->response["final"];
  }

}


