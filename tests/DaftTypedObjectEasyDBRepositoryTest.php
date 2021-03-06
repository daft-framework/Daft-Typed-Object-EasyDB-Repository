<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject;

use ParagonIE\EasyDB\EasyDB;
use ParagonIE\EasyDB\Factory;

/**
 * @template S as array{id:int, name:string}
 * @template S2 as array{id:int|string, name:string}
 * @template S3 as array{name:string}
 * @template T as array<string, scalar|array|object|null>
 * @template T1 as Fixtures\MutableForRepository
 * @template T2 as Fixtures\EasyDBTestRepository
 *
 * @template-extends DaftTypedObjectRepositoryTest<S, S2, S3, T, T1, T2>
 */
class DaftTypedObjectEasyDBRepositoryTest extends DaftTypedObjectRepositoryTest
{
	/**
	 * @return list<
	 *	array{
	 *		0:class-string<T2>,
	 *		1:array{type:class-string<T1>},
	 *		2:list<S>,
	 *		3:list<S2>
	 *	}
	 * >
	 */
	public function dataProviderAppendTypedObject() : array
	{
		$out = [
			[
				Fixtures\EasyDBTestRepository::class,
				[
					'type' => Fixtures\MutableForRepository::class,
					EasyDB::class => Factory::create('sqlite::memory:'),
					'table' => 'foo',
				],
				[
					[
						'id' => 0,
						'name' => 'foo',
					],
				],
				[
					[
						'id' => '1',
						'name' => 'foo',
					],
				],
			],
		];

		if ('true' === getenv('TRAVIS')) {
			$out[] = [
				Fixtures\EasyDBTestRepository::class,
				[
					'type' => Fixtures\MutableForRepository::class,
					EasyDB::class => Factory::create(
						'mysql:host=localhost;dbname=travis',
						'travis',
						''
					),
					'table' => 'foo',
				],
				[
					[
						'id' => 0,
						'name' => 'foo',
					],
				],
				[
					[
						'id' => '1',
						'name' => 'foo',
					],
				],
			];
		}

		/**
		 * @var list<
		 *	array{
		 *		0:class-string<T2>,
		 *		1:array{type:class-string<T1>},
		 *		2:list<S>,
		 *		3:list<S2>
		 *	}
		 * >
		 */
		return $out;
	}

	/**
	 * @return list<
	 *	array{
	 *		0:class-string<T2>,
	 *		1:array{type:class-string<T1>, table:string, ParagonIE\EasyDB\EasyDB:EasyDB},
	 *		2:S,
	 *		3:S3,
	 *		4:S2
	 *	}
	 * >
	 */
	public function dataProviderPatchObject() : array
	{
		$out = [
			[
				Fixtures\EasyDBTestRepository::class,
				[
					'type' => Fixtures\MutableForRepository::class,
					EasyDB::class => Factory::create('sqlite::memory:'),
					'table' => 'foo',
				],
				[
					'id' => 0,
					'name' => 'foo',
				],
				[
					'name' => 'bar',
				],
				[
					'id' => '1',
					'name' => 'bar',
				],
			],
		];

		if ('true' === getenv('TRAVIS')) {
			$out[] = [
				Fixtures\EasyDBTestRepository::class,
				[
					'type' => Fixtures\MutableForRepository::class,
					EasyDB::class => Factory::create(
						'mysql:host=localhost;dbname=travis',
						'travis',
						''
					),
					'table' => 'foo',
				],
				[
					'id' => 0,
					'name' => 'foo',
				],
				[
					'name' => 'bar',
				],
				[
					'id' => '1',
					'name' => 'bar',
				],
			];
		}

		/**
		 * @var list<
		 *	array{
		 *		0:class-string<T2>,
		 *		1:array{type:class-string<T1>, table:string, ParagonIE\EasyDB\EasyDB:EasyDB},
		 *		2:S,
		 *		3:S3,
		 *		4:S2
		 *	}
		 * >
		 */
		return $out;
	}

	/**
	 * @dataProvider dataProviderAppendTypedObject
	 *
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::__construct()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::ForgetTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::MaybeRecallTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::RecallTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::RemoveTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::__construct()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::AppendTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::ConvertObjectToSimpleArray()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::ConvertSimpleArrayToObject()
	 *
	 * @param class-string<T2> $repo_type
	 * @param array{type:class-string<T1>} $repo_args
	 * @param list<S> $append_these
	 * @param list<S2> $expect_these
	 */
	public function test_append_typed_object(
		string $repo_type,
		array $repo_args,
		array $append_these,
		array $expect_these
	) : void {
		parent::test_append_typed_object(
			$repo_type,
			$repo_args,
			$append_these,
			$expect_these
		);
	}

	/**
	 * @dataProvider dataProviderAppendTypedObject
	 *
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::__construct()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::ObtainIdFromObject()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::RecallTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::__construct()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::ConvertSimpleArrayToObject()
	 *
	 * @depends test_append_typed_object
	 *
	 * @param class-string<T2> $repo_type
	 * @param array{type:class-string<T1>} $repo_args
	 * @param list<S> $append_these
	 * @param list<S2> $expect_these
	 */
	public function test_default_failure(
		string $repo_type,
		array $repo_args,
		array $append_these,
		array $expect_these
	) : void {
		parent::test_default_failure(
			$repo_type,
			$repo_args,
			$append_these,
			$expect_these
		);
	}

	/**
	 * @dataProvider dataProviderAppendTypedObject
	 *
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::__construct()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::ObtainIdFromObject()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::RecallTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::__construct()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::ConvertSimpleArrayToObject()
	 *
	 * @depends test_append_typed_object
	 *
	 * @param class-string<T2> $repo_type
	 * @param array{type:class-string<T1>} $repo_args
	 * @param list<S> $append_these
	 * @param list<S2> $expect_these
	 */
	public function test_custom_failure(
		string $repo_type,
		array $repo_args,
		array $append_these,
		array $expect_these
	) : void {
		parent::test_custom_failure(
			$repo_type,
			$repo_args,
			$append_these,
			$expect_these
		);
	}

	/**
	 * @dataProvider dataProviderPatchObject
	 *
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::__construct()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::ObtainIdFromObject()
	 * @covers \SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectEasyDBRepository::RecallTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::__construct()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::AppendObject()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::AppendTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::ConvertObjectToSimpleArray()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::ConvertSimpleArrayToObject()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::ForgetTypedObject()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::PatchTypedObjectData()
	 * @covers \SignpostMarv\DaftTypedObject\Fixtures\EasyDBTestRepository::UpdateTypedObject()
	 *
	 * @depends test_append_typed_object
	 *
	 * @param class-string<T2> $repo_type
	 * @param array{type:class-string<T1>} $repo_args
	 * @param S $append_this
	 * @param S3 $patch_this
	 * @param S2 $expect_this
	 */
	public function test_patch_object(
		string $repo_type,
		array $repo_args,
		array $append_this,
		array $patch_this,
		array $expect_this
	) : void {
		parent::test_patch_object(
			$repo_type,
			$repo_args,
			$append_this,
			$patch_this,
			$expect_this
		);

		$repo = new $repo_type(
			$repo_args
		);

		$object_type = $repo_args['type'];

		$object = $object_type::__fromArray($append_this);

		$fresh = $repo->AppendTypedObject($object);

		$fresh->name = strrev($fresh->name);

		$repo->UpdateTypedObject($fresh);

		$repo->ForgetTypedObject($repo->ObtainIdFromObject($fresh));

		$fresh2 = $repo->RecallTypedObject($repo->ObtainIdFromObject($fresh));

		static::assertNotSame($fresh, $fresh2);

		static::assertSame(strrev($object->name), $fresh2->name);
	}
}
