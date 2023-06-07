# Codeigniter DataTables
Integrate DataTables to CodeIgniter 4.

## Installation

Install with Composer:

```shell
composer require dginanjar/codeigniter-datatables
```

## Usage

There are two ways to use this package. Using trait or using static method.

### Using trait

Put the following code into the model:

```php
use CodeIgniter\Model;

class Foo extends Model
{
    use \Dginanjar\CodeIgniterDataTable\Trait\DataTable;

    protected $table = 'foo';
    ...
}
```

With this trait, the `datatable()` method is added to the model class. Next, in your controller you can write the following:

```php
$model = new \App\Models\Foo();
$result = $model
    ->select('foo.*, tablename.fieldname')
    ->join('tablename', 'tablename.id = foo.tablename_id')
    ->datatable();

return $this->response->setJSON($result);
```

### Using static method

You can directly use the `DataTable::get()` static method in the following way.

```php
$model = new \App\Models\Foo();
$result = DataTable::get(
    ->select('foo.*, bar.x AS bar_x, bar.y AS bar_y')
    ->join('bar', 'bar.id = foo.bar_id')
);

return $this->response->setJSON($result);
```

### View

This is example JavaScript code in view.

```js
<script>
$(document).ready(function () {
    $("#foo").DataTable({
        processing: true,
        serverSide: true,
        ajax: "http://localhost/foo/datatable",
        columns: [
            { data: "a" },
            { data: "b" },
            { data: "c" },
            {
                data: "bar.x",
                visible: false,
                render: () => null
            },
            {
                data: "bar.y",
                render: (data, type, row, meta) => {
                    return row.bar_y
                }
            }
        ],
    });
});
</script>
```