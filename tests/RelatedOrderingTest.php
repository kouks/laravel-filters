<?php

class RelatedOrderingTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->makeModels(20);
    }

    /** @test */
    public function it_filters_by_related_table_id()
    {
        $request = $this->mockRequest('rid', 'desc');
        $filter = new RelatedOrderFilter($request);

        $result = TestModel::filter($filter)->get();

        $this->assertTrue($result->first()->relatedModel->id > $result->last()->relatedModel->id);
    }

    /** @test */
    public function it_filters_by_any_column_on_a_related_table()
    {
        $request = $this->mockRequest('rstring', 'asc');
        $filter = new RelatedOrderFilter($request);

        $result = TestModel::filter($filter)->get();

        $this->assertTrue($result->first()->relatedModel->string < $result->last()->relatedModel->string);
    }
}

class RelatedOrderFilter extends \Koch\Filters\Filter
{
    protected $orderable = [
        'rid' => 'related_models.id',
        'rstring' => 'related_models.string',
    ];
}
