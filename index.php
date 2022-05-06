<?php

/*
USAGE
* list($filename, $total) = getSumation("A.txt");

*/
function getSumation($filename = 'A.txt', $result){
  $ext = strtolower(pathinfo($filename,   PATHINFO_EXTENSION);
    if( is_file($filename) && $ext)
     ){
         $handle = fopen($filename, "r");
         if ($handle) {
              while (($line = fgets($handle)) !== false) {
                  // process the line read.
                   if(is_file($line)){
                     getSumation($line,$result)
                   }else{
                    $results[] = $line  
                   }
              }
              fclose($handle);
          } else {
              // error opening the file.
            return json_encode(["message"=>"error opening the file"]);
          } 
    }else{
       return json_encode(["message"=>"error opening the file"]);
    }
    $total_sumation = array_sum($result);
    $response = array(
      "filename" => $filename,
      "sum" =>  $total_sumation,
    );

    return $response;
}
list($filename, $total) = getSumation("A.txt");

