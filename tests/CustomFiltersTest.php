<?php

class CustomFiltersTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->makeModels(20);
    }

    /** @test */
    public function it_lets_you_create_custom_filters()
    {
        $request = $this->mockRequest('numberEquals', 5);
        $filter = new CustomFilter($request);

        $res1 = TestModel::filter($filter)->get();
        $res2 = TestModel::where('number', 5)->get();

        $this->assertEquals($res1, $res2);
    }
}

class CustomFilter extends \Koch\Filters\Filter
{
    public function numberEquals($number)
    {
        $this->builder->where('number', $number);
    }
}
