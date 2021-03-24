<?php

namespace Sprite;

class Group
{
    /**
     * Store config data.
     * 
     * @var array
     */

    private $config;

    /**
     * Store sprite instance.
     * 
     * @var \Sprite\Sprite
     */

    private $context;

    /**
     * Store sprite group name.
     * 
     * @var string
     */

    private $name;

    /**
     * Store sprite group source path.
     * 
     * @var string
     */

    private $path;

    /**
     * Store sprite overlay color.
     * 
     * @var string
     */

    private $color;

    /**
     * Store sprite group image files.
     * 
     * @var array
     */

    private $images = [];

    /**
     * Create a new instance of sprite group.
     * 
     * @param   array $config
     * @param   \Sprite\Sprite $context
     * @return  void
     */

    public function __construct(array $config, Sprite $context)
    {
        $this->config = $config;
        $this->context = $context;
        $this->setConfiguration();
        $this->getMemberImages();
    }

    /**
     * Set sprite configuration data.
     * 
     * @return  void
     */

    private function setConfiguration()
    {
        $this->name             = $this->config['name'] ?? null;
        $this->path             = $this->config['path'] ?? null;
        $this->color            = $this->config['color'] ?? null;
    }

    /**
     * Gather all images for this sprite group.
     * 
     * @return  void
     */

    private function getMemberImages()
    {
        $path = $this->context->root() . $this->path;
        $sort = $this->context->config('settings')['sort'];

        // Test if path exist and readable.
        if(file_exists($path) && is_readable($path))
        {
            foreach(array_diff(scandir($path), array('.', '..')) as $file)
            {
                $this->images[] = new Image($path, $this->name, $file);
            }
        }

        // If sort property is true, sort images by size.
        if($sort)
        {
            usort($this->images, function($a, $b) {
                return $a->getWidth() > $b->getWidth();
            });
        }
    }

    /**
     * Return array of images per group.
     * 
     * @return  array
     */

    public function getImages()
    {
        return $this->images;
    }

    /**
     * Return sprite group name.
     * 
     * @return  string
     */

    public function getName()
    {
        return $this->name;
    }

    /**
     * Return sprite group source path.
     * 
     * @return  string
     */

    public function getPath()
    {
        return $this->path;
    }

    /**
     * Return sprite group overlay color.
     * 
     * @return  string
     */

    public function getColor()
    {
        return $this->color;
    }

}