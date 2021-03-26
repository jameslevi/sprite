# Sprite

![](https://img.shields.io/badge/packagist-v1.0.6-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a) ![](https://img.shields.io/badge/license-MIT-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a)
 
Is a simple image to sprite compiler tool for web development.

**FEATURES**  
1. Generate multiple image collections.
2. Supports png, gif, jpg, bmp and webp icons.
3. Automatically generate css class for each icon.

**INSTALLATION**  
1. You can install via composer using this command *"composer require jameslevi/sprite"*.
2. Copy the sprite.json and sprite file from jameslevi/sprite directory from the vendor folder.  

**GETTING STARTED**  
1. Set the directory from sprite.json where the sprite will be created.
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
3. Put all the icons in to the path specified.
4. Run the command *"php sprite --generate"* in your terminal.
5. Add the generated css file named "sprite.css" in the HEAD section of your HTML page.  
```html
<link rel="stylesheet" href="sprite/sprite.css">
```  
6. Declare an icon by **sprite-[group_name]-[image_name]** class template.
```html
<span class="sprite-test-image1"></span>
```  
8. For more info, run *"php sprite --help"* in your terminal.  

**BEST PRACTICES**  
Group images that are same sizes and types.  

**CONTRIBUTE**  
For bug reports and suggestions, you can email James Levi Crisostomo via nerdlabenterprise@gmail.com.  

**LICENSE**  
This package is an open-sourced software licensed under [MIT](https://opensource.org/licenses/MIT) License.
