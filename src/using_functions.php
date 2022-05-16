<?php

$response = [
  "keys" => [],
  "values" => [], 
  "final" => []
];

/**
 * resolve the summation
 * @param  array $response
 * @return array
 */
function resolveSummation(array $response): array
{
    for(
		$i = count($response["values"]),
        $j = count($response["values"]) -1; $i > 0,
        $j > -1; 
        $i--, $j--
	) {
		// on each loop slice out certain number of values from the response set
		$resolutionSum = array_slice( $response["values"], 0, $i);
        array_sum($resolutionSum);
		
		// arrange key pair of resulting responses
        // sum of all numbers in file
		$response["final"][$response["keys"][$j]] = array_sum($resolutionSum);
	}
    
	return $response["final"];
}


/**
 * Summation function
 *
 * @param  string $filename
 * @param  array  $order
 * @return array
 * @throws \InvalidArgumentException
 */
function getSummation(string $filename, array $order = []): array
{
	global $response;
    // bail if file does not exist
	
	if(!file_exists($filename)){
		throw new \InvalidArgumentException("File does not exist");
	}
	
	$filename = basename(realpath(dirname(__FILE__).'/'.$filename));  
	$order = []; // holds the sequence of file and numbers extraction
	$lines = file($filename, FILE_SKIP_EMPTY_LINES); // read the file
	$order[] = $filename; // for key pair ordering key==filename, val == array(n) 0<=n<=infinity  
	$i =0;

    /* Read through each lines 
	*  Run recursive function if a .txt file is found in the line
	*  if found  a .txt file, rerun iteration and file extraction
	*/
	foreach($lines as $key => $fileItem)
	{
		// simple check if we find a . (dot) extention to denote our feed line is a file encounter    
		$last_period = strrpos($fileItem, '.');
		if($last_period !== false) {
			// recursion reiteration reloop  into the newly found file
            // and run extraction into $order
            array_push($order, getSummation(trim($fileItem), $order));
		} else {
			// if we find a number then just append to order
			array_push($order, $fileItem);
		}        
	}  
	
	// summation of numbers found in each files
	$total_sumation = array_sum($order);
	$order["sum"] = $total_sumation;
	
	$i = 0;
	// sort individual key pairs from file extracted order
	
	foreach($order as $pairKey => $val) {
		// filter values
		if(!is_numeric($pairKey) && !is_array($val)) {
			array_push($response['values'], $val);
		} else if(!is_array($val) && preg_match_all('/[.a-zA-Z0-9]+\.txt/', $val, $matches)) {
			//filter keys, sum of all numbers in file excluding filenames found in file A.txt
			array_push($response['keys'], $val);
		}
	}
	
	//total summation resolution per index
	$result = resolveSummation($response);
	return $result;
}

?>

<pre>
<?php

print_r(
	getSummation("A.txt")
);

?>
</pre>
