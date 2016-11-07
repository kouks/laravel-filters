<?php

class SimpleSearchingTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->makeModels(20);
    }

    /** @test */
    public function it_searches_in_specified_column()
    {
        $request = $this->mockRequest('search', '1');
        $filter = new SimpleSearchFilter($request);

        $result = TestModel::filter($filter)->get();

        $this->assertEquals(1, $result->first()->id);
        $this->assertCount(11, $result); // Because 1, 11, 12, 13 .. 19
    }

    /** @test */
    public function it_searches_in_specified_column_by_a_pattern()
    {
        $request = $this->mockRequest('search', 'as');
        $filter = new SimpleSearchFilter($request);

        TestModel::filter($filter)->get()->each(function ($el) {
            $this->assertContains('as', strtolower($el->string));
        });
    }
}

class SimpleSearchFilter extends \Koch\Filters\Filter
{
    protected $searchable = [
        'id',
        'string',
    ];
}
