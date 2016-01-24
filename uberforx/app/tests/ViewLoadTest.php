<?php

class ViewLoadTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testLoadHome()
	{
		$this->call('GET','/');
		$this->assertResponseOk();
	}
}
