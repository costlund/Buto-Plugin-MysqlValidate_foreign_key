# Buto-Plugin-MysqlValidate_foreign_key
This form validator could be used in a delete form. It will check all reference tables for data related to current record.

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
In this example we check for all foreign_keys refer to table child for form field child_id.
```
items:
  child_id:
    type: hidden
    label: 'Child'
    mandatory: true
    default: rs:child_id
    validator:
      -
        plugin: mysql/validate_foreign_key
        method: validate
        data:
          reference_table_name: 'child'
```
