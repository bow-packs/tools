**Bow\Tools**
-------------
A collection of usefull classes and components

* _ValidationGenerator_ 
    
    > simple management of your validation rules

To add:

* _IndexResourceRequest_
 
    > handle requests to index-resources in a clean, eloquent way 



**ValidationGenerator**
-----------------------
Usage:

```php
use Bow\Tools\ValidationGenerator;
$rules = new ValidationGenerator($arrayOfRules, $namespace);
```

Example:

Setup your rules eg. in ResourceController constructor

```php

class ResourceController {

    protected $rules;

    public function __construct()
    {
        // setup
        $this->rules = new ValidationGenerator([
            
            'email' => 'email|unique:users,email',
            'password' => 'same:password_repeat',
            'password_repeat' => 'same:password',
            'api_token' => 'string',
            'status' => 'integer'
        ]);
    }

    public function store()
    {
        $this->validate(
            $request, 
            $this->rules->required()->getStoreRules()
        ); 
    }
    
    public function update() 
    {
        $this->validate(
            $request,
            $this->rules
                ->exclude(['api_token'])
                ->filled()
                ->getUpdateRules()
        );
    }

}
```
It is also possible to extend ValidationGenerator, so you have one point to where you manage the rules for a model.


_Namespace?_

The `namespace` attribute is used for complex form handlings and produces rules aka:

```php 
["$namespace.email" => "required"]
```

So you can mix up different rulesets in big forms






