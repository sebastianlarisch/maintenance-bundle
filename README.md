# Maintenance Bundle

## Install

This Symfony bundle shows a maintenance page if mode is enabled via environment variable.
The maintenance page can be bypassed via a configurable cookie or a configurable allowed IP address. This ensures, that the maintenance mode is only visible for the audience.

**Install this bundle via Composer:**

`composer require php-translation/symfony-bundle`

**Add configuration yaml file**
Then, configure the bundle. An example configuration looks like this:

```
maintenance:
  enabled: '%env(bool:MAINTENANCE_ENABLED)%'
  cookie_bypass_token: 'bypass'
  ip_addresses: "127.0.0.1,1.2.3.4"
  excluded_paths: '/admin,/uploads'
  get_bypass_name: foo
  get_bypass_value: bar
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

Create a cookie with name `maintenance_bypass` and add value of your `cookie_bypass_token`

**Bypass with GET param**

Provide name and value of GET parameter to your maintenance.yml and add it to your GET request URL.
