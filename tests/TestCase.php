<?php

use Koch\Filters\Behavior\Filterable;
use Illuminate\Database\Capsule\Manager as DB;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    protected $faker;

    public function setUp()
    {
        $this->setUpDatabase();
        $this->migrateTables();

        $this->faker = Faker\Factory::create();
    }

    protected function setUpDatabase()
    {
        $db = new DB;

        $db->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $db->bootEloquent();
        $db->setAsGlobal();
    }

    protected function migrateTables()
    {
        DB::schema()->create('test_models', function ($table) {
            $table->increments('id');
            $table->integer('related_model_id')->nullable();
            $table->string('string');
            $table->integer('number');
            $table->timestamps();
        });

        DB::schema()->create('related_models', function ($table) {
            $table->increments('id');
            $table->string('string');
            $table->integer('number');
            $table->timestamps();
        });
    }

    protected function makeModel($fields = null, $model = null)
    {
        $fields = $fields ?: [
            'string' => $this->faker->word,
            'number' => rand(1, 100),
        ];

        $model = $model ? new $model($fields) : new TestModel($fields);

        $model->save();

        return $model;
    }

    protected function makeModels($count = 5)
    {
        foreach (range(1, $count) as $i) {
            $r = $this->makeModel(null, 'RelatedModel');
            $m = $this->makeModel();
            $r->testModels()->save($m);
        }
    }

    protected function mockRequest($key, $value)
    {
        $_GET[$key] = $value;

        return Illuminate\Http\Request::capture();
    }
}

class TestModel extends \Illuminate\Database\Eloquent\Model
{
    use Filterable;

    protected $guarded = [];

    public function relatedModel()
    {
        return $this->belongsTo(RelatedModel::class);
    }
}

class RelatedModel extends \Illuminate\Database\Eloquent\Model
{
    use Filterable;

    protected $guarded = [];

    public function testModels()
    {
        return $this->hasMany(TestModel::class);
    }
}
