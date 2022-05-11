<?php

/**
 * Function getSumation
 *
 * @param  string $filename The starting file to iterate over
 * @param  array $result The result holder 

 * Usage and description
 *  $startFile = "A.txt";
 *  print_r(getSumation($startFile));
 * A function that calculates the sum of numbers in a file having
 * other file names inclusive with nums in each of them
 */

$response = [
  "keys"=>[],
  "values"=>[], 
  "final"=>[]
];


/**
 * Function getSumation
 *
 * 
 * @param  array $result The result holder 

 * Usage and description
 *  $startFile = "A.txt";
 *  print_r(getSumation($startFile));
 * A function that resolves two array pairs into an associative array
 * by calculating summation of each indexed iter prior to the other
 */if(!function_exists("resolveSummation")){
  function resolveSummation(array $response): array{
     // run logic conditions
     for( 
        $i=count($response["values"]),
        $j =count($response["values"])-1; $i>0,
        $j>-1; 
        $i--, $j--
    ){
      // on each loop slice out certain number of values from the response set
       $resolutionSum = array_slice( $response["values"],0, $i);
       $summ = array_sum($resolutionSum);
      // arrange key pair of resulting responses
       //eg : $response["A.txt"] = sum of all numbers in file including filenames found in file A.txt and other files recursively linked to A.txt by sub files in its file content stream
       $response["final"][$response["keys"][$j]] =array_sum($resolutionSum);

    }
    //   $response["final"] = array_unique($response["final"]);
    //shiny bits response
    return $response["final"];
  }
}

function getSumation(string $filename, array $orderings=[]): array
{
  global $response;
  /*bail if file does not exist
   * basically fails safe if the input data is not a file of file
   * relative path does not exist
  */

  if(!file_exists($filename)){
    throw new \InvalidArgumentException("File does not exist");
  }
  $startFile = $filename;
  $filename = basename(realpath(dirname(__FILE__).'/'.$filename));  
  // $ext = strtolower(pathinfo($filename,   PATHINFO_EXTENSION));
  $orderings = []; //holds the sequence of file and numbers extraction
  $lines = file($filename,FILE_SKIP_EMPTY_LINES); // read the file
  $orderings[] = $filename; // for key pair ordering key==filename, val == array(n) 0<=n<=infinity  
  $i =0;
  /*Read through each lines 
   * Run recursive function if a .txt file is found in the line
   * if found  a .txt file, rerun iteration and file extraction
  */
  foreach( $lines  as $key => $fileItem )
  {
     /*simple check if we find a . (dot) extention to denote our feed line is a file encounter*/    
     $last_period = strrpos($fileItem, '.');
     if($last_period !== false){
        //recursion reiteration reloop  into the newly found file
        // and run extraction into $orderings
        array_push($orderings, getSumation(trim($fileItem),$orderings) );
     }else{
       /*if we find a number then just append to orderings*/
        array_push($orderings , $fileItem);
     }        
  }  

  //summation of numbers found in each files
  $total_sumation =  array_sum($orderings) ;
  $orderings["sum"] = $total_sumation;

  $i = 0;
  // sort individual key pairs from file extracted orderings
  foreach($orderings as $pairKey =>$val){
    // filter values
    if (!is_numeric($pairKey) && !is_array($val)) {
      array_push($response['values'], $val);
    }else if( !is_array($val) && preg_match_all('/[.a-zA-Z0-9]+\.txt/', $val, $matches)){
      //filter keys eg $response["A.txt"] = sum of all numbers in file excluding filenames found in file A.txt
       array_push($response['keys'], $val);
    }
  }
  
  //total summation resolution per index iter
  $result = resolveSummation($response);
  return $result;
 
}

?>

<pre>
<?php
print_r(
  getSumation("A.txt")
 );

?>
</pre>
