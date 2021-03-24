<?php

namespace Sprite;

class Builder
{
    /**
     * Store current instance object.
     * 
     * @var \Sprite\Builder
     */

    private static $instance;

    /**
     * Store sprite instance object.
     * 
     * @var \Sprite\Sprite
     */

    private $context;

    /**
     * Store sprite group objects.
     * 
     * @var array
     */

    private $groups = [];

    /**
     * Create new builder instance.
     * 
     * @param   \Sprite\Sprite $context
     * @return  void
     */

    private function __construct(Sprite $context)
    {
        $this->context = $context;
        $this->getGroups();

        // Check if sprite group is not empty.
        if(empty($this->groups))
        {
            Console::error('No sprite groups has been declared.');
        }

        $output = null;

        // Execute cache clear.
        exec('php sprite -x', $output);
        
        // Display cache clear response message.
        foreach($output as $item)
        {
            echo $item . "\n";
        }

        $this->build();
    }

    /**
     * Compile sprite groups.
     * 
     * @return  void
     */

    private function getGroups()
    {
        Console::warn('Gathering sprite data.');

        foreach($this->context->config('sprites') as $sprite)
        {
            $this->groups[] = new Group($sprite, $this->context);
        }
    }

    /**
     * Start building sprites.
     * 
     * @return  void
     */

    private function build()
    {
        $config = $this->context->config();
        $generator = [];
        $css = [];

        foreach($this->groups as $group)
        {
            $images = $group->getImages();
            $name = $group->getName();
            $max_width = $this->context->settings('max-width');
            $min_size = $this->context->settings('min-size');
            $max_size = $this->context->settings('max-size');
            $quality = $this->context->settings('quality');
            $tiles = [];
            $width = 0;
            $height = 0;
            $largest_height = 0;
            $largest_heights = [];
            $canvas_width = 0;
            $canvas_height = 0;
            $n = 0;

            foreach($images as $image)
            {
                $n++;

                // Check if image has acceptable width and height.
                // echo $image->getWidth() . "\n";
                if($image->exist() && $image->getWidth() >= $min_size && $image->getHeight() >= $min_size && $image->getWidth() <= $max_size && $image->getHeight() <= $max_size)
                {
                    $x = $width;
                    $y = $height;
                    $z = false;

                    // Always update the largest height.
                    if($image->getHeight() > $largest_height)
                    {
                        $largest_height = $image->getHeight();
                    }

                    $tiles[] = new Tile($image, $x, $y);

                    // Set canvas width and tile x coordinate.
                    if(($width + $image->getWidth()) > $width && ($width + $image->getWidth()) <= $max_width)
                    {
                        $width += $image->getWidth();
                    }

                    // Set canvas width.
                    if($width > $canvas_width)
                    {
                        $canvas_width = $width;
                    }

                    // Set canvas height.
                    if($n == sizeof($images) && !$z)
                    {
                        $z = true;
                        $largest_heights[] = $largest_height;
                    }

                    // Reset the width variable if coordinate + width is more
                    // than the maximum width allowed.
                    if(($width + $image->getWidth()) > $max_width)
                    {
                        $width = 0;
                        $height += $largest_height;

                        if(!$z)
                        {
                            $largest_heights[] = $largest_height;
                        }

                        $largest_height = 0;
                    }
                }
            }

            // Compute the canvas height.
            $canvas_height = array_sum($largest_heights);

            // Generate the new sprite.
            $generator = new Generator($this->context, $name, $group, $tiles, $canvas_width, $canvas_height, $config['path']);
            $generator->generate($quality);

            // Implode all stylesheets per group.
            $css[] = implode('', $generator->getStylesheet());
        }

        Console::success('Sprite images was successfully generated.');

        // Generate sprite stylesheet file.

        $path = $this->context->root() . str_replace('/', '\\', $this->context->config('path')) . '\\sprite.css';
        $file = fopen($path, 'w');
        fwrite($file, implode('', $css));
        fclose($file);
        
        Console::success('Sprite stylesheet was successfully generated.');
    }

    /**
     * Initiate sprite builder class.
     * 
     * @param   \Sprite\Sprite $context
     * @return  \Sprite\Builder
     */

    public static function init(Sprite $context)
    {
        if(is_null(static::$instance))
        {
            static::$instance = new self($context);
        }

        return static::$instance;
    }

}