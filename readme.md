# Laravel Filters

[![Build Status](https://travis-ci.org/kouks/laravel-filters.svg?branch=master)](https://travis-ci.org/kouks/laravel-filters)
[![StyleCI](https://styleci.io/repos/73021743/shield?branch=master)](https://styleci.io/repos/73021743)


## Contents

- [Installation](#installation)
- [Usage](#usage)
- [FAQ](#faq)

## Installation

### Composer

Open your console and `cd` into your Laravel project. Run:

```bash
composer require kouks/laravel-filters
```

And you are all set up!

## Usage

### Creating filters

You can store your filters anywhere in your project but I advise to use the `app/Filters` directory to keep thing constistent. In your filters directory, create a new filter class as in following example. __Not that we suppose that we have a `Post` model with the `id`, `title`, `body` and `active` columns including timestamps.__

```php
namespace App\Filters;

use Koch\Filters\Filter;

class PostFilter extends Filter
{
    //
}
```

Now you can specify your _orderable_ and _searchable_ columns.

```php
namespace App\Filters;

use Koch\Filters\Filter;

class PostFilter extends Filter
{
    protected $searchable = ['id', 'title', 'body'];
    
    protected $orderable = [
        'id' => 'id',
        'title' => 'title',
    ];
}
```

__Note that all the filters take data from the query strings in you url. The above example will react to query strings in format e. g. `/?title=asc` or `/?search=pattern`.__

__Also note that oderable array is a key - value pair. This is because the key corresponds to the query string name, whereas the value corresponds to a database column.__

### Setting up the filters

__Simple searching__

Your class for simple searching could look as follows.

```php
namespace App\Filters;

use Koch\Filters\Filter;

class PostFilter extends Filter
{
    protected $searchable = ['id', 'title', 'body'];
}
```

This setup reacts to the url query string in format `/?search=pattern` and will return all the results that match the search pattern in specified columns.

__Simple ordering__

Simple ordering class could look like this.

```php
namespace App\Filters;

use Koch\Filters\Filter;

class PostFilter extends Filter
{
    protected $orderable = [
        'id' => 'id',
        'topic' => 'title',
    ];
}
```

This setup allows you to use the query strings in format `/?id={desc/asc}` or `/?topic={desc/asc}`. Note that we specified that the `topic` query string points to the `title` database column.

__Searching in related tables__

Your class for related searching could look as follows. __For this example we suppose to have the `Author` model with the columns of `id` and `name`, which has a `one - many` relationship with the `Post` model.__

```php
namespace App\Filters;

use Koch\Filters\Filter;

class PostFilter extends Filter
{
    protected $searchable = ['id', 'title', 'authors.id', 'authors.name'];
}
```

This format allows us to include the author's `name` and `id` in the results. Simple as that, however, __note that this will work only for `one - many` and `one - one` relationships, which are properly set up, following the Laravel conventions.__

__Orderng by related table's columns__

Related ordering is set up as in the folowing example. __For this example we suppose to have the `Author` model with the columns of `id` and `name`, which has a `one - many` relationship with the `Post` model.__

```php
namespace App\Filters;

use Koch\Filters\Filter;

class PostFilter extends Filter
{
    protected $orderable = [
        'id' => 'id', 
        'title' => 'title', 
        'author_id' => 'authors.id', 
        'author_name' => 'authors.name',
    ];
}
```

This format allows us to order also by the author's `name` and `id` with the query string of `/?{author_id/author_name}={desc/asc}`. Simple as that, however, __note that this will work only for `many - one` and `one - one` relationships, which are properly set up, following the Laravel conventions.__


__Custom filters__

You are also allowed to setup you own filters by creating new methods on the filter class itself. Consider following example:

```php
namespace App\Filters;

use Koch\Filters\Filter;

class PostFilter extends Filter
{
    public function popular($direction)
    {
        return $this->builder->orderBy('votes', $direction);
    }
}
```

Above example will correspond to the query string in the format `/?popular={desc/asc}`. The query string name corresponds to the name of the method and its value is passed as an argument. Note that you are able to access the parent class `builder` property and adjust it accordingly.

### Abstracion

This package comes with the `Koch\Filters\Contracts\Filter` interface, which allows you to make your own implementations of the filter.

## FAQ

Nobody has ever asked me anything about this package so I can't determine the frequency of questions.
