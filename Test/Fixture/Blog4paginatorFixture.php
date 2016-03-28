<?php
/**
 * BlogFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlogFixture', 'Blogs.Test/Fixture');

/**
 * BlogFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Blogs\Test\Fixture
 * @codeCoverageIgnore
 */
class Blog4paginatorFixture extends BlogFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Blog';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//Blog 1
		array(
			'id' => '1',
			'block_id' => '1',
			'key' => 'Blog_1',
			'name' => 'Blog name 1',
			//'language_id' => '1',
		),
		array(
			'id' => '2',
			'block_id' => '2',
			'key' => 'Blog_1',
			'name' => 'Blog name 1',
			//'language_id' => '2',
		),
		//Blog 2
		array(
			'id' => '3',
			'block_id' => '4',
			'key' => 'Blog_2',
			'name' => 'Blog name 2',
			//'language_id' => '2',
		),
		//Blog 3
		array(
			'id' => '4',
			'block_id' => '6',
			'key' => 'Blog_3',
			'name' => 'Blog name 2',
			//'language_id' => '2',
		),

		//101-200まで、ページ遷移のためのテスト
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		for ($i = 101; $i <= 200; $i++) {
			$this->records[$i] = array(
				'id' => $i,
				'block_id' => $i,
				'key' => 'Blog_' . $i,
				'name' => 'Blog_name_' . $i,
				//'language_id' => '2',
			);
		}
		parent::init();
	}

}
