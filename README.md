# Sprite

![](https://img.shields.io/badge/packagist-v1.0.8-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a) ![](https://img.shields.io/badge/license-MIT-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a)
 
Is a simple image to sprite compiler tool for web development.

## Features ##
1. Supports png, gif, jpg, bmp and webp icons.
2. Automatically generate css class for each icon.

## Installation ##
1. You can install via composer.
```
composer require jameslevi/sprite --dev
```
2. Copy the atmos file from vendor/jameslevi/atmos folder to your root directory.  
3. Create a new folder in your root directory named commands.

## Getting Started ##
1. Create a new atmos command file named "sprite".
```
php atmos --make sprite
```
2. Open the Sprite.php file from commands folder and add a new protected method named "generate".
```php
/**
 * Method to generate sprite image and stylesheet.
 *
 * @param  array $arguments
 * @return void
 */

protected function generate(array $arguments)
{
     
}
```
3. Try the following code inside the generate method.
```php
// Create new instance by setting sprite name and asset location.
$sprite = new Sprite("test", __DIR__ . "/../resources/icons");

// Set where to save the generated sprite image.
$sprite->setGeneratedImagePath(__DIR__ . "/../public/img");
$sprite->setImageBaseURL("img/");

// Set where to save the generated css file.
$sprite->setGeneratedCSSPath(__DIR__ . "/../public/css");

$sprite->generate();
```
4. Run the following command in your terminal to generate sprite.
```
php atmos sprite:generate
```
5. An image and css file will be generated in your public folder. Add the css in the head section of your webpage to access all sprites. The file name of your css file will be *sprite-{sprite name}.css*.
```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/sprite-test.css">
</head> 
<body>
  
</body>
</html>
```
6. You can access each icons using css classes in the following pattern *sprite-{sprite name}-{icon name}*.
```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/sprite-test.css">
</head> 
<body>
 
    <div class="sprite-test sprite-test-icon1"></div>
    <div class="sprite-test sprite-test-icon2"></div>
    <div class="sprite-test sprite-test-icon3"></div>
 
</body>
</html> 
```
7. If needed to add new icons in to your icon set, just run the generate command and will regenerate new sprite image and css.
```
php atmos sprite:generate
```

## Best Practices ##
Always make it a practice to group all images that are same sizes and types.  

## Contribution ##
For bug reports and suggestions, you can email James Levi Crisostomo via nerdlabenterprise@gmail.com.  

## License ##
This package is an open-sourced software licensed under [MIT](https://opensource.org/licenses/MIT) License.
