## Model Filters

### What are Model Filters?
Model Filters are used to simplify complex query logic in Laravel applications. They are separate PHP class files that can be used in controllers, action classes or any other PHP classes to filter data from an Eloquent model.

> By using Model Filters, you can keep your controllers and action classes clean and simple, while moving complex query logic into separate filter class files for better organization and maintainability.

### How to Use Model Filters
To use Model Filters in your Laravel application, follow these steps:

1. Create a new PHP class file in the `app\Filters` directory.
2. The new filter class should extend the `App\Filters\Filters` base class.
3. Use the `HasFilters` trait to the Eloquent model you want to filter.
    ```php
    use App\Traits\HasFilters;
    ```
4. Add the `$filters` property to the Eloquent model to define which filter class file to use. This property should contain the name of the filter class, for example `UserFilters::class`.
    ```php
    protected static string $filters = UserFilters::class;
    ```
5. You can now filter the Eloquent model data using the `filter()` method. For example, to filter the `App\Models\User` model data by a search keyword, you can use the following code:
    ```php
    use App\Models\User;
  
    User::filter(['search' => 'keyword'])->get();
    
    // OR
    
    User::filter(request()->all())->get();
  
    // OR
    
    User::filter(request()->only('search'))->get();
    ```
