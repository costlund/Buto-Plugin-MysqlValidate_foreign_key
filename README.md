# Buto-Plugin-MysqlValidate_foreign_key
This form validator could be used in a delete form. It will check all reference tables for data related to current record.

For example. If you have table account and want to delete a record by it´s id. This plugin check all tables related to account with restrict on delete. If any records find an validation error occure.

## Settings
Add mysql settings to theme /config/settings.yml.
```
plugin:
  mysql:
    validate_foreign_key:
      enabled: true
      data:
        mysql: 'yml:/../buto_data/mysql.yml'
```

## Form validator
In this example we check for all foreign_keys referrer to table account where restrict is set on delete.
```
items:
  account_id:
    type: hidden
    label: 'Account'
    mandatory: true
    default: rs:account_id
    validator:
      -
        plugin: mysql/validate_foreign_key
        method: validate
        data:
          reference_table_name: 'account'
```
