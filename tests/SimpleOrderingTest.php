<?php

class SimpleOrderingTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->makeModels(20);
    }

    /** @test */
    public function it_filters_by_specified_column()
    {
        $request = $this->mockRequest('id', 'desc');
        $filter = new SimpleOrderFilter($request);

        $result = TestModel::filter($filter)->get();

        $this->assertTrue($result->first()->id > $result->last()->id);
    }

    /** @test */
    public function it_filters_by_specified_string_column()
    {
        $request = $this->mockRequest('string', 'asc');
        $filter = new SimpleOrderFilter($request);

        $result = TestModel::filter($filter)->get();

        $this->assertTrue($result->first()->string < $result->last()->string);
    }

    /** @test */
    public function it_uses_aliases_for_ordering()
    {
        $request = $this->mockRequest('test', 'desc');
        $filter = new SimpleOrderFilter($request);

        $result = TestModel::filter($filter)->get();

        $this->assertTrue($result->first()->number > $result->last()->number);
    }
}

class SimpleOrderFilter extends \Koch\Filters\Filter
{
    protected $orderable = [
        'id' => 'id',
        'string' => 'string',
        'test' => 'number',
    ];
}
