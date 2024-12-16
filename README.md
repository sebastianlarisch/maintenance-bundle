# Maintenance Bundle

## Install

**Install this bundle via Composer:**

`composer require php-translation/symfony-bundle`

**Add configuration yaml file**
Then, configure the bundle. An example configuration looks like this:

```
maintenance:
  enabled: '%env(bool:MAINTENANCE_ENABLED)%'
  bypass_token: 'bypass'
```

**Add bundle to your bundles.php**

```
Larisch\MaintenanceBundle\MaintenanceBundle::class => ['all' => true],
```

**Clear cache**

Clear cache with following command.

```
bin/console cache:clear
```

**Bypass with cookie**

Create a cookie with name `maintenance_bypass` and add value of your `bypass_token`
