<?php

use PHPUnit\Framework\TestCase;

class SumTest extends TestCase 
{
  private $sum;

  protected function setUp(): void
  {
    parent::setUp();
    $this->sum = new Sum();
  }

  protected function tearDown(): void
  {
    $this->sum = NULL;
    parent::tearDown();
  }

  /**
   * @group file
   * @test
   */
	public function testInexistentFileException() {
	    $this->expectException(\InvalidArgumentException::class);
      $this->expectExceptionMessage('File does not exist');

      $this->sum->getSummation("X.txt");
	}

  /**
   * @group file
   * @test
   */
	public function testResultsValue(){
      $this->assertEquals($this->sum->getSummation("A.txt" ), [
          "A.txt" => 111,
          "B.txt" => 39,
          "C.txt" => 12
      ]);
	}
}