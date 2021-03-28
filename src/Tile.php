<?php

namespace Sprite;

class Tile
{
    /**
     * Store image object.
     * 
     * @var \Sprite\Image
     */

    private $image;

    /**
     * Horizontal coordinate of the tile.
     * 
     * @var int
     */

    private $x;

    /**
     * Vertical coordinate of the tile.
     * 
     * @var int
     */

    private $y;

    /**
     * Create new tile instance.
     * 
     * @param   \Sprite\Image $image
     * @param   int $x
     * @param   int $y
     * @return  void
     */

    public function __construct(Image $image, int $x, int $y)
    {
        $this->image = $image;
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Return image resource.
     * 
     * @return  resource
     */

    public function getResource()
    {
        $extension = $this->image->getExtension();
        $location = $this->image->getLocation();

        if($extension === 'png')
        {
            return imagecreatefrompng($location);
        }
        else if($extension === 'jpg')
        {
            return imagecreatefromjpeg($location);
        }
        else if($extension === 'gif')
        {
            return imagecreatefromgif($location);
        }
        else if($extension === 'bmp')
        {
            return imagecreatefrombmp($location);
        }
        else if($extension === 'webp')
        {
            return imagecreatefromwebp($location);
        }
    }

    /**
     * Return image object.
     * 
     * @return  \Sprite\Image
     */

    public function getImage()
    {
        return $this->image;
    }

    /**
     * Return X coordinate.
     * 
     * @return  int
     */

    public function getX()
    {
        return $this->x;
    }

    /**
     * Return Y coordinate.
     * 
     * @return  int
     */

    public function getY()
    {
        return $this->y;
    }

    /**
     * Return tile width.
     * 
     * @return  int
     */

    public function getWidth()
    {
        return $this->image->getWidth();
    }

    /**
     * Return tile height.
     * 
     * @return  int
     */

    public function getHeight()
    {
        return $this->image->getHeight();
    }

}