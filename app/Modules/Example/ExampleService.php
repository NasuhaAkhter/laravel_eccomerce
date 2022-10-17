<?php

namespace App\Modules\Example;
use App\Queries\UserQuery;
use App\commonClass\commonService;
use App\commonClass\commonQuery;
class ExampleService
{
    use UserQuery;
    use commonService;
    use commonQuery;
    public function exampleMethod(){
        return 'this is returning from example service';
    }
}
