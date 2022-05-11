<?php
  require "vendor/autoload.php";
  
  $startFile ="A.txt";
  $summation = new Sum();
  $result = $summation->getSumation($startFile);
 
?>
<pre>
<?php
  print_r($result);
?>
</pre>