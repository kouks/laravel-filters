<?php

class RelatedSearchingTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->makeModels(20);
    }

    /** @test */
    public function it_searches_in_related_table_ids()
    {
        $request = $this->mockRequest('search', '1');
        $filter = new RelatedSearchFilter($request);

        $result = TestModel::filter($filter)->get();

        $this->assertEquals(1, $result->first()->relatedModel->id);
        $this->assertCount(11, $result); // Because 1, 11, 12, 13 .. 19
    }

    /** @test */
    public function it_searches_in_related_table_by_a_pattern()
    {
        $request = $this->mockRequest('search', 'as');
        $filter = new RelatedSearchFilter($request);

        TestModel::filter($filter)->get()->each(function ($el) {
            $this->assertContains('as', strtolower($el->relatedModel->string));
        });
    }
}

class RelatedSearchFilter extends \Koch\Filters\Filter
{
    protected $searchable = [
        'related_models.id',
        'related_models.string',
    ];
}
