<?php

/**
 * This is a post-installation script runned
 * each time this package was installed by
 * composer.
 * 
 * @author  James Levi Crisostomo
 * @version v1.0.4
 */

// Create a new folder named sprites.
$dir = __DIR__ . '/sprites';

if(!file_exists($dir))
{
    mkdir($dir);
}

// Copy the sprite.json and cmd file in to the root directory.
$files = [
    'sprite.json',
    'sprite'
];

foreach($files as $file)
{
    if(file_exists($file) && !file_exists(__DIR__ . '/' . $file))
    {
        copy($file, __DIR__ . '/' . $file);
    }
}