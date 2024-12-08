# Maintenance Bundle

## Install

Install this bundle via Composer:

`composer require php-translation/symfony-bundle

Then, configure the bundle. An example configuration looks like this:

```
maintenance:
  enabled: '%env(bool:MAINTENANCE_ENABLED)%'
  bypass_token: 'foobar'
```

