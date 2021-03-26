# Sprite

![](https://img.shields.io/badge/packagist-v1.0.5-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a) ![](https://img.shields.io/badge/license-MIT-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a)
 
Is an image compiler written in PHP created for web developers. Sprite takes away the hard work of compiling icons using traditional ways.

**FEATURES**  
1. Generate multiple image collections.
2. Supports png, gif, jpg, bmp and webp icons.
3. Automatically generate css class for each icon.

**INSTALLATION**  
1. You can install via composer using this command *"composer require-dev jameslevi/sprite"*.
2. Copy the sprite.json and sprite file from jameslevi/sprite directory from the vendor folder.
3. Create a folder in your resources directory for storing source icons.
4. Create a folder from your public directory for storing your sprites.

**GETTING STARTED**  
1. Set the path where sprite will be saved from your sprite.json file.
```json
"path": "/public/sprite"
```  
2. Add new sprite group in your sprite.json file under the sprites property.
```json
"sprites": [
   {
      "name": "test",
      "path": "/resources/test"
   }
]
```  
3. Paste the icons to be compiled to the path declared.
4. Run the command *"php sprite --generate"* in your terminal.
5. Add the generated css file named "sprite.css" in the HEAD section of your HTML page.  
```html
<link rel="stylesheet" href="sprite/sprite.css">
```  
6. Declare an icon by **sprite-[group_name]-[image_name]** class template.
```html
<span class="sprite-test-image1"></span>
```  
7. You can toggle icon using hover by declaring the class with two leading dashes.
```html
<span class="sprite-test-image1 --sprite-test-image2"></span>
```  
8. For more info, run *"php sprite --help"* in your terminal.  

**BEST PRACTICES**  
Group images that are same sizes and types.  

**CONTRIBUTE**  
For bug reports, commits and suggestions, you can email James Levi Crisostomo via nerdlabenterprise@gmail.com.  

**LICENSE**  
This package is an open-sourced software licensed under [MIT](https://opensource.org/licenses/MIT) License.
