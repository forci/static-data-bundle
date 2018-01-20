# Installation 

`composer require forci/static-data-bundle`

Add the bundle to your bundles array
```php
new \Forci\Bundle\StaticDataBundle\ForciStaticDataBundle(),
```

Configure your bundles. These are the bundles that have StaticData that needs to be imported
```yaml
forci_static_data:
    bundles:
        - App
        # Note: Your bundle can be configured as a string or full-blown config.
        # You can also specify your entity manager if not "default" or if you have many
        # For advanced usage, please refer to the Configuration file of this bundle.
        - Api
        -
           bundle: Frontend
           # Note: When looking up classes, this bundle converts slashes to namespace separators
           directory: Some/Directory 
        - Admin
```

```php
<?php

// src/App/StaticData/RoleData.php

namespace App\StaticData;

use App\Entity\Role;
use Forci\Bundle\StaticDataBundle\StaticData\StaticData;

class RoleData extends StaticData {

    public function doLoad() {
        $records = [
            Role::ID_ADMINISTRATOR => 'Administrator',
            Role::ID_TRANSLATOR => 'Translator',
        ];

        foreach ($records as $id => $name) {
            if (!$this->find(Role::class, $id)) {
                $record = new Role();
                $record->setId($id);
                $record->setName($name);
                $this->persist($record);
            }
        }
    }
}
```

The Static Data bundle will look into each `bundle`'s configured `directory` and pick all `*Data.php` files.
Then, if there is a service with an ID equal to the FQCN, or if the class is a subclass of `Forci\Bundle\StaticDataBundle\StaticData\StaticData`, it will be constructed and added to a ``Forci\Bundle\StaticDataBundle\StaticData\DataCollection`.
Note, that this only happens on-demand and will *NOT* slow down your application's performance besides having to load another bundle and process its configs.

# Usage

All you need to do is run the `./bin/console forci_static_data:import` command.
You can also add this command to your deployment flow. This way, adding new static entities is a breeze. 
This is especially useful when development happens by multiple developers, in multiple different branches and you want to keep migrations clean.
Having multiple branches and doing refactoring in these often leads to unexpected crashes upon deployment to production due to the way DoctrineMigrationsBundle works.

If you would like to import the static data for only one bundle, run `./bin/console forci_static_data:import -b YourBundle` or `./bin/console forci_static_data:import --bundle=YourBundle`

# Advanced Usage

This bundle registers two services:
- `forci_static_data.data_finder` - instance of `Forci\Bundle\StaticDataBundle\StaticData\DataFinder`
- `forci_static_data.data_loader` - instance of `Forci\Bundle\StaticDataBundle\StaticData\DataLoader`

You can use those to find and/or load your static data in any way you would like - you can embed it in your own commands.

