<?php

namespace Sprite;

class Generator
{
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
     * List of tiles in each sprite group.
     * 
     * @var array
     */

    private $tiles;

    /**
     * Sprite canvas width.
     * 
     * @var int
     */

    private $width;

    /**
     * Sprite canvas height.
     * 
     * @var int
     */

    private $height;

    /**
     * Sprite storage path.
     * 
     * @var string
     */

    private $path;

    /**
     * List of stylesheets per sprite.
     * 
     * @var array
     */

    private $css = [];

    /**
     * Create new instance of this class.
     * 
     * @param   \Sprite\Sprite $sprite
     * @param   string $name
     * @param   array $tiles
     * @param   int $width
     * @param   int $height
     * @param   string $path
     */

    public function __construct(Sprite $sprite, string $name, array $tiles, int $width, int $height, string $path)
    {
        $this->context      = $sprite;
        $this->name         = $name;
        $this->tiles        = $tiles;
        $this->width        = $width;
        $this->height       = $height;
        $this->path         = $path;
    }

    /**
     * Generate sprite image.
     * 
     * @param   int $quality
     * @return  void
     */

    public function generate(int $quality = 9)
    {
        $path = $this->context->root() . str_replace('/', '\\', $this->path);

        // Check if directory exist.
        if(file_exists($path))
        {
            $canvas = imagecreatetruecolor($this->width, $this->height);
            $copies = [];

            // Set canvas transparent background.
            imagesavealpha($canvas, true);
            imagealphablending($canvas, false);
            imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 255, 255, 255, 127));

            $this->css[] = ".sprite-" . $this->name . "{background-image:url('sprite-" . $this->name . ".png') !important;}";
            
            // Draw each tile inside the canvas.
            foreach($this->tiles as $tile)
            {
                $image = $tile->getImage();
                $resource = $tile->getResource();

                imagecopy($canvas, $resource, $tile->getX(), $tile->getY(), 0, 0, $tile->getWidth(), $tile->getHeight());
                $copies[] = $resource;
                
                // Class as an alternative for img tag.
                $css = ".sprite-" . $this->name . "-" . $image->getName() . "{display:block;";
                $css .= "background-repeat:no-repeat;background-color:transparent;";
                $css .= "width:" . $tile->getWidth() . "px !important;height:" . $tile->getHeight() . "px !important;";
                $css .= "background-position:" . ($tile->getX() * -1) . "px " . ($tile->getY() * -1) . "px !important;";
                $css .= "background-image:url('sprite-" . $this->name . ".png') !important;";
                $css .= "}";

                // Class with pseudo hover action.
                $css .= ".--sprite-" . $this->name . "-" . $image->getName() . ":hover{";
                $css .= "width:" . $tile->getWidth() . "px !important;height:" . $tile->getHeight() . "px !important;";
                $css .= "background-position:" . ($tile->getX() * -1) . "px " . ($tile->getY() * -1) . "px !important;";
                $css .= "background-image:url('sprite-" . $this->name . ".png') !important;";
                $css .= "}";

                $this->css[] = $css;
            }

            // Create sprite and free from memory.
            imagepng($canvas, $path . '\sprite-' . $this->name . '.png', $quality);
            imagedestroy($canvas);

            // Destroy all image copies.
            foreach($copies as $copy)
            {
                imagedestroy($copy);
            }
        }
        else
        {
            Console::error('Path to where generated sprite will be save is missing.');
        }
    }

    /**
     * Return generated css classes.
     * 
     * @return  array
     */

    public function getStylesheet()
    {
        return $this->css;
    }

}