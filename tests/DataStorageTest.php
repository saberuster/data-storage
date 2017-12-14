<?php

namespace SaberRuster\DataStorage\Tests;


use SaberRuster\DataStorage\DataStorage;
use SaberRuster\DataStorage\EqualInterface;

class DataStorageTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @group        DataStorageTest
     * @dataProvider rangeDataProvider
     */
    public function testForeach( $source, ...$result )
    {
        $storage = new DataStorage($source);
        foreach ( $storage as $k => $value ) {
            $this->assertEquals($result[ $k ], $value);
        }
    }

    /**
     * @group DataStorageTest
     */
    public function testContains()
    {
        $storage = new DataStorage([ 'a', 'b', 'c' ]);
        $this->assertTrue($storage->contains('c'), 'c is not in storage');
        $this->assertTrue($storage->contains('b'), 'v is not in storage');
        $this->assertTrue($storage->contains('a'), 'a is not in storage');
        $this->assertTrue(!$storage->contains('d'), 'd is in storage');
        $storage->attach('d');
        $this->assertTrue($storage->contains('d'), 'd is not in storage after attach');
        $storage->detach('d');
        $this->assertTrue(!$storage->contains('d'), 'd is in storage after detach');

        $v1            = $this->newItem(1);
        $cp_v1         = $this->newItem(1);
        $v2            = $this->newItem(2);
        $v3            = $this->newItem(3);
        $v4            = $this->newItem(4);
        $cp_v4         = $this->newItem(4);
        $equal_storage = new DataStorage([ $v1, $v2, $v3 ]);
        $this->assertTrue($equal_storage->contains($cp_v1), 'cp_v1 is not in equal_storage');
        $this->assertTrue(!$equal_storage->contains($cp_v4), 'cp_v4 is in equal_storage');
        $equal_storage->attach($v4);
        $this->assertTrue($equal_storage->contains($cp_v4), 'cp_v4 is not in equal_storage after attach');
        $equal_storage->detach($v4);
        $this->assertTrue(!$equal_storage->contains($cp_v4), 'cp_v4 is in equal_storage after detach');
    }

    public function newItem( $v )
    {
        $item       = $this->equalDatum();
        $item->item = $v;

        return $item;
    }


    public function equalDatum()
    {
        return new class implements EqualInterface
        {

            public $item;

            public function equal( $item )
            {
                return $this->item == $item->item;
            }
        };
    }


    public function rangeDataProvider()
    {
        return [
            [ [ 1, 2, 3 ], 1, 2, 3 ]
        ];
    }
}
