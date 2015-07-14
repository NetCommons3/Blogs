<?php
/**
 * All Test
 */
/**
 * BlogEntriesEdit All Test Suite
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case
 * @codeCoverageIgnore
 */
class BlogEntriesEditControllerTest extends CakeTestSuite {

/**
 * All test suite
 *
 * @return CakeTestSuite
 */
	public static function suite() {
		$contoller = preg_replace('/^([\w]+)ControllerTest$/', '$1', __CLASS__);

		$suite = new CakeTestSuite(sprintf('All %s Controller tests', $contoller));
		$path = __DIR__ . DS . $contoller;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
