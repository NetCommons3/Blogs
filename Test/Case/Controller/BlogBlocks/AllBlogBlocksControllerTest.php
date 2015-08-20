<?php
/**
 * All Test
 */
/**
 * BlogBlocks All Test Suite
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case
 * @codeCoverageIgnore
 */
class AllBlogBlocksControllerTest extends CakeTestSuite {

/**
 * All test suite
 *
 * @return CakeTestSuite
 */
	public static function suite() {
		$contoller = preg_replace('/^All([\w]+)ControllerTest$/', '$1', __CLASS__);

		$suite = new CakeTestSuite(sprintf('All %s Controller tests', $contoller));
		$path = __DIR__;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
