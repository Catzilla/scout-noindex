# scout-noindex

[![GitHub license](https://img.shields.io/github/license/Catzilla/scout-noindex.svg)](https://github.com/Catzilla/scout-noindex/blob/master/LICENSE)

This package provides a simple way to prevent indexing certain model fields when you using [Laravel Scout](https://github.com/laravel/scout).

By default, every time you save Eloquent model, `saved` event will fire. As result, `MakeSearchable` job will dispatch even if nothing changed in model (see Scout issue [#285](https://github.com/laravel/scout/issues/285)).

For example:
```php
// Retrieving first entry from database
$model = User::first();
// Nothing changed here, saving model
$model->save();
// Database query not performed, but MakeSearchable job dispatched
```

Another case, when you only want to search model by some fields, for example by `name` and `login`. You change something another, but `MakeSearchable` job will dispatch:
```php
// Retrieving first entry from database
$model = User::first();
// Changing email (NOT name or login)
$model->email = 'mail@example.com';
// Saving model
$model->save();
// email field updated, and MakeSearchable job dispatched even if we don't need to search by email
```

This package helps solve this issues in easy way.

## Installing

You can install this package via Composer:
```
composer require catzilla/scout-noindex
```

## Usage

In your model file simply replace `Searchable` trait with provided by this package.

Optional, you can use `$index` and `$noindex` properties to include or exclude fields from search syncing.
```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Catzilla\ScoutNoindex\Searchable;

class User extends Model
{
    use Searchable;
    
    /**
     * Array of fields to search.
     * When this property present in model, only this fields will be searchable
     *
     * @var array
     */
    protected $index = [
        'name',
        'login'
    ];
    
    /**
     * Array of fields to exclude from syncing.
     * When this property present in model, any changes in this fields will not trigger search syncing.
     *
     * @var array
     */
    protected $noindex = [
        'email',
        'updated_at'
    ];
}
```

## License

scout-noindex is licensed under the [MIT license](http://opensource.org/licenses/MIT)
