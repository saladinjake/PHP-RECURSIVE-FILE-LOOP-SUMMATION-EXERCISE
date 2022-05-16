<?php

class Sum {

	/**
	 * @var array
	 */
	public $response;

	/**
	 * @var array
	 */
	public $fileHandler;

	/**
	 * @var string
	 */
	public $currentFile;
	
	function __construct(){
       
        $this->response =  [
		    "keys" => [],
		    "values" => [], 
		    "final" => []
		];
	}

	/**
	 * Summation function
	 *
	 * @param  string $filename
	 * @param  array  $bufferStack
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getSummation(string $filename, array $bufferStack = []): array
	{
		// bail if file does not exist

	    if(!file_exists($filename)){
			throw new \InvalidArgumentException("File does not exist");
		}

	    $this->currentFile = $filename;
	    $bufferStack = []; // holds the sequence of file and numbers extraction
	    $this->fileHandler = file($filename, FILE_SKIP_EMPTY_LINES); // read the file

	    $bufferStack[] = $filename; // for key pair ordering key==filename, val == array(n) 0<=n<=infinity  
	    $i =0;

	    /* Read through each lines 
	    *  Run recursive function if a .txt file is found in the line
	    *  if found  a .txt file, rerun iteration and file extraction
	    */
	    foreach($this->fileHandler  as $key => $inputFeed) {
	     
			/* simple check if we find a . (dot) extention to denote our feed line is a file encounter */    
	        $last_period = strrpos($inputFeed, '.');

			// make it strict
	        if($last_period !== false) {
				// recursion reiteration reloop  into the newly found file
	            // and run extraction into $order
				array_push($bufferStack, $this->getSummation(trim($inputFeed), $bufferStack));
			} else {
				/* if we find a number then just append to order */
				array_push($bufferStack, $inputFeed);
			}        
		}  

	    // summation of numbers found in each files
	    $total_sumation =  array_sum($bufferStack);
	    $bufferStack["sum"] = $total_sumation;

	    $i = 0;

	    // sort individual key pairs from file extracted order
	    foreach($bufferStack as $pairKey => $val){
			// filter values
			if (!is_numeric($pairKey) && !is_array($val)) {
				array_push($this->response['values'], $val);
			} elseif( !is_array($val) && preg_match_all('/[.a-zA-Z0-9]+\.txt/', $val, $matches)) {
				//filter keys, sum of all numbers in file excluding filenames found in file A.txt
				array_push($this->response['keys'], $val);
			}
		}
		// total summation resolution per index
		$result = $this->resolveSummation($this->response);
		return $result;
	}

	/**
	 * resolve the summation
	 * 
	 * @return array
	 */
	private function resolveSummation(): array
	{
		for(
			$i = count($this->response["values"]),
			$j = count($this->response["values"]) -1; $i > 0,
			$j > -1; 
			$i--, $j--
		) {
			// on each loop slice out certain number of values from the response set
            $resolutionSum = array_slice($this->response["values"], 0, $i);
            array_sum($resolutionSum);

			// arrange key pair of resulting responses
			// sum of all numbers in file
			$this->response["final"][$this->response["keys"][$j]] = array_sum($resolutionSum);
		}

		return $this->response["final"];
	}
}


