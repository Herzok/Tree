<?php

use PHPUnit\Framework\TestCase;

require_once('Tree.php');

class TreeTest extends TestCase
{
    public function testShowFullTree()
    {
    	$expected = "111111";
		$tree = new Tree('tree', true);
    	ob_start();
        $tree->show();
        $actual = ob_get_contents();
        ob_end_clean();
       	$this->assertEquals($expected,$actual);
    }
    public function testShowDirsTree()
	{
		$expected = "";
		$tree = new Tree('tree', false);
		ob_start();
		$tree->show();
		$actual = ob_get_contents();
		ob_end_clean();
		$this->assertEquals($expected,$actual);
	}

}
