<?php

namespace Sprite;

class Image
{
    /**
     * Contains all image types supported.
     * 
     * @var array
     */

    private $support = [
        'png',
        'jpg',
        'gif',
        'bmp',
        'webp',
    ];

    /**
     * Store image path.
     * 
     * @var string
     */

    private $path;

    /**
     * Store image group name.
     * 
     * @var string
     */

    private $group;

    /**
     * Store image filename.
     * 
     * @var string
     */

    private $filename;

    /**
     * Store image name.
     * 
     * @var string
     */

    private $name;

    /**
     * Store image file extension.
     * 
     * @var string
     */

    private $extension;

    /**
     * Return image width.
     * 
     * @var int
     */

    private $width;

    /**
     * Return image height.
     * 
     * @var int
     */

    private $height;

    /**
     * Create new image instance.
     * 
     * @param   string $path
     * @param   string $group
     * @param   string $filename
     * @return  void
     */

    public function __construct(string $path, string $group, string $filename)
    {
        $this->path = $path;
        $this->group = $group;
        $this->filename = $filename;

        // Slice filename to get file name and extension.
        $split = explode('/', $filename);
        $split = explode('.', $split[sizeof($split) - 1]);

        $this->name = strtolower($split[0]);
        $this->extension = strtolower($split[1]);

        // Get the width and height of the image.
        if($this->exist())
        {
            list($width, $height) = getimagesize($this->getLocation());

            $this->width = $width;
            $this->height = $height;
        }
    }

    /**
     * Return image location.
     * 
     * @return  string
     */

    public function getLocation()
    {
        return str_replace('/', '\\', $this->path) . '\\' .  $this->filename;
    }

    /**
     * Return image path.
     * 
     * @return  string
     */

    public function getPath()
    {
        return $this->path;
    }

    /**
     * Return image name.
     * 
     * @return  string
     */

    public function getName()
    {
        return $this->name;
    }

    /**
     * Return image file extension.
     * 
     * @return  string
     */

    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Return true if image file is supported.
     * 
     * @return  bool
     */

    public function supported()
    {
        return in_array($this->extension, $this->support);
    }

    /**
     * Return the filename of the image.
     * 
     * @return  string
     */

    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Check if image exist.
     * 
     * @return  bool
     */

    public function exist()
    {
        return file_exists($this->getLocation()) && is_readable($this->getLocation());
    }

    /**
     * Return image width.
     * 
     * @return  int
     */

    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Return image height.
     * 
     * @return  int
     */

    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Return name of group where image belong.
     * 
     * @return  string
     */

    public function getGroupName()
    {
        return $this->group;
    }

}