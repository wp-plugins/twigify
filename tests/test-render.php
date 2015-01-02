<?php

use \ContentTemplates\View;

class SampleTest extends WP_UnitTestCase {

	function setUp() {
		\WP_Mock::setUp();
	}

	function tearDown() {
		\WP_Mock::setUp();
	}

	function testVars() {
		// replace this with some actual testing code
		$view = new View();
		$content = 'loremipsumsuckerpunch{{ test }}';
		$expected = 'loremipsumsuckerpunchsuccess';
		$output = $view->render($content, array('test'=>'success'));
		$this->assertEquals($output, $expected);
	}

	function testFunctions() {
		global $post;
	//	add_action('the_content', array('\ContentTemplates\View','hook'), -1);
		$test_id = wp_insert_post(
			array(
				'post_content' => "test {{ function.the_title() }}
				{{ function.the_category() }}{{ function.the_author() }}",
				'post_title'=>'TESTTITLE',
				'post_author' => 1,
				'post_category' => array(1)
			)
		);
		$view = new View();
		$this->assertNotNull($test_id);
		$post = get_post($test_id);
		setup_postdata($post);
		ob_start();
			the_content();
		$content = ob_get_clean();
		$this->assertContains("TESTTITLE", $content);
		$this->assertContains("admin", $content);
		$this->assertContains("Uncategorized", $content);
		wp_delete_post($test_id);
	}
}
