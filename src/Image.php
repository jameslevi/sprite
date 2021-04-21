<?php

namespace Sprite;

class Image
{
    /**
     * Location of the image file.
     * 
     * @var string
     */

    private $file;

    /**
     * Store image width in px.
     * 
     * @var int
     */

    private $width;

    /**
     * Store image height in px.
     * 
     * @var int
     */

    private $height;

    /**
     * Construct a new image instance.
     * 
     * @param   string $file
     * @return  void
     */
    
    public function __construct(string $file)
    {
        $this->file = $file;

        if($this->exists())
        {
            list($width, $height)   = getimagesize($this->file);
            $this->width            = $width;
            $this->height           = $height;
        }
    }

    /**
     * Return the image path.
     * 
     * @return  string
     */

    public function getFileLocation()
    {
        return $this->file;
    }

    /**
     * Return true if image exists.
     * 
     * @return  bool
     */

    public function exists()
    {
        return file_exists($this->file);
    }

    /**
     * Return the file name of the image.
     * 
     * @return  string
     */

    public function getName()
    {
        return pathinfo($this->file, PATHINFO_FILENAME);
    }

    /**
     * Return the file extension of this image.
     * 
     * @return  string
     */

    public function getExtension()
    {
        return pathinfo($this->file, PATHINFO_EXTENSION);
    }

    /**
     * Return the width of an image in px.
     * 
     * @return  int
     */

    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Return the height of an image in px.
     * 
     * @return  int
     */

    public function getHeight()
    {
        return $this->height;
    }

}