<?php
use PHPUnit\Framework\TestCase;
require "../src/Sum.php";
final class ClassSummationTest extends TestCase{
	/** @test */
	public function testFileDontExist(){
	    $sum  = new Sum();//since this is a classmap via composer its globally existing
      $this->expectException(\Exception::class);
      $sum->getSumation("Filedoesnotexist.none");
	}
    /** @test */
	public function testResultsValue(){
       $sum  = new Sum();
        $this->assertEquals($sum->getSumation("A.txt" ), [
          "A.txt" => 111,
          "B.txt" => 39,
          "C.txt" => 12
        ]);
	}
}