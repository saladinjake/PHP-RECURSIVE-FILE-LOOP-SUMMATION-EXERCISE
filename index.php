<?php
  require "vendor/autoload.php";
  
  $startFile ="A.txt";
  $summation = new Sum();
  $result = $summation->getSummation($startFile);
 
?>
<pre>
<?php
  print_r($result);
?>
</pre>