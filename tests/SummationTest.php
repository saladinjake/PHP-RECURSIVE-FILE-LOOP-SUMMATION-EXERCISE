<?php
use PHPUnit\Framework\TestCase;

final class SummationTest extends TestCase{
	/** @test */
	public function testFileDontExist(){
	
      $this->expectException(\Exception::class);
      getSumation("Filedoesnotexist.none");
	}
    /** @test */
	public function testResultsValue(){
        $this->assertEquals(getSumation("A.txt" ), [
          "A.txt" => 111,
          "B.txt" => 39,
          "C.txt" => 12
        ]);
	}
}