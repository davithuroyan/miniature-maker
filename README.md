<p align="center">
    <h1 align="center">Miniature Generation Service</h1>
    <br>
</p>


How to Install & Config
-------------------

Run following commands to install 

```
git clone https://github.com/davithuroyan/miniature-maker.git

composer install
```

Open ``config/db.php`` and replace DB connection credentials

You can change watermark and generated miniatures paths in ``config/params.php`` 
```php
return [
    'watermark_path' => '/images/watermark.png',
    'save_path' => '/images/generated',
];

```

How to run
------------

Run following commands to generate miniatures

```
php yii miniature-generator/run {sizes} {w} {c}
```

1. to generate miniature with sizes ``150x250`` without watermark and catalog only
```
php yii miniature-generator/run 150x250 0 1
``` 
2. to generate miniature with sizes ``150x250``, ``100x100``, ``170x170`` with watermark 
```
php yii miniature-generator/run 150x250,100,170x170 1 0
```
