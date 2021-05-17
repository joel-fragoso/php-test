<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class FileCollectionTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $collection = new FileCollection('file.json');
        return $collection;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded()
    {
        $collection = new FileCollection('file.json');
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);
        $collection->set('index4', 6.5);
        $collection->set('index5', ['data']);
    }

    /**
    * @test
    * @depends dataCanBeAdded
    */
    public function dataCanBeRetrieved()
    {
        $collection = new FileCollection('file.json');
        $collection->set('index1', 'value');

        $this->assertEquals('value', $collection->get('index1'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function inexistentIndexShouldReturnDefaultValue()
    {
        $collection = new FileCollection('file.json');

        $this->assertNull($collection->get('inexistentIndex'));
        $this->assertEquals('defaultValue', $collection->get('inexistentIndex', 'defaultValue'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function collectionWithItemsShouldReturnValidCount()
    {
        $collection = new FileCollection('file.json');
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);

        $this->assertEquals(5, $collection->count());
    }

    /**
     * @test
     * @depends collectionWithItemsShouldReturnValidCount
     */
    public function collectionCanBeCleaned()
    {
        $collection = new FileCollection('file.json');
        $collection->set('index1', 'value');
        $this->assertEquals(5, $collection->count());

        $collection->clean();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function addedItemShouldExistInCollection()
    {
        $collection = new FileCollection('file.json');
        $collection->set('index1', 'value');

        $this->assertTrue($collection->has('index1'));
    }

    /**
     * @test
     */
    public function shouldBeAbleToVerifyTheFileExists()
    {
        $this->expectExceptionMessage('File does not exists');

        $collection = new FileCollection('inexistentFile.json');
        return $collection;
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function expiredItemsShouldNotBeShow()
    {
        $collection = new FileCollection('file.json');
        $collection->set('index1', 'value', -1);

        $this->assertNull($collection->get('index1'));
    }
}
