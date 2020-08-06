# Tooling

```
composer install noud/politie-open-data-api
```

Use [erd-js](https://github.com/noud/erd-js) to transform the .er to React.js Entity-relationship diagram front end .json.
```
cd ../erd-js && npm transform
```

Import in React.js Entity-relationship diagram front end

Export Laravel databases migrations

```
php artisan migrate
php artisan code:models
```

Browse to ```http://politie.localhost/politiebureaus/all``` and have the data in ```public/politiebureaus.json```.