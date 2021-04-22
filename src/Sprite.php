<?php

namespace Sprite;

use Atmos\Console;

class Sprite
{
    /**
     * Current version of sprite.
     * 
     * @var string
     */

    private static $version = 'v1.0.9';

    /**
     * List of supported image file extensions.
     * 
     * @var array
     */

    private $supported = array(
        'png',
        'jpg',
        'gif',
        'bmp',
        'webp',
    );

    /**
     * Name of sprite group to generate.
     * 
     * @var string
     */

    private $name;

    /**
     * Location where to find the icons to compile.
     * 
     * @var string
     */

    private $location;

    /**
     * Default maximum width of generated image.
     * 
     * @var int
     */

    private $max_width = 720;

    /**
     * Default minimum dimension of each icons.
     * 
     * @var int
     */

    private $min_size = 16;

    /**
     * Default maximum dimension of image icons.
     * 
     * @var int
     */

    private $max_size = 32;

    /**
     * Rendering quality of sprite image.
     * 
     * @var int
     */

    private $quality = 9;

    /**
     * Path where to save generated sprite image.
     * 
     * @var string
     */

    private $image_path;

    /**
     * The actual URL of the generated sprite image.
     * 
     * @var string
     */

    private $base_url;

    /**
     * Path where to save generated stylesheet file.
     * 
     * @var string
     */

    private $css_path;

    /**
     * Store list of all images to compile.
     * 
     * @var array
     */

    private $images = array();

    /**
     * Store list of image file extensions in this group.
     * 
     * @var array
     */

    private $extensions = array();

    /**
     * Construct a new instance of sprite.
     * 
     * @param   string $name
     * @param   string $location
     * @return  void
     */

    public function __construct(string $name, string $location)
    {
        if(in_array(substr($location, strlen($location) - 1, strlen($location)), ['/', '\\']))
        {
            $location = substr($location, 0, strlen($location) - 1);
        }

        $this->name         = strtolower($name);
        $this->location     = str_replace('/', '\\', $location);
        
        $this->readImages();
    }

    /**
     * Get the list of all image files.
     * 
     * @return  void
     */

    private function readImages()
    {
        if(file_exists($this->location) && is_readable($this->location))
        {
            $images = array();

            foreach(array_diff(scandir($this->location), array('.', '..')) as $file)
            {
                if($this->isValidImageType($file))
                {
                    $type = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    if(!in_array($type, $this->extensions))
                    {
                        $this->extensions[] = $type;
                    }

                    $images[] = new Image($this->location . '/' . $file);
                }
            }

            usort($images, function($a, $b) {
                return $a->getWidth() > $b->getWidth();
            });

            $this->images = $images;
        }
    }

    /**
     * Return true if image file is supported.
     * 
     * @param   string $file
     * @return  bool
     */

    private function isValidImageType(string $file)
    {
        return in_array(pathinfo($file, PATHINFO_EXTENSION), $this->supported);
    }

    /**
     * Return true if GD library is enabled.
     * 
     * @return  bool
     */

    private function isGDEnabled()
    {
        return extension_loaded('gd');
    }

    /**
     * Return sprite name.
     * 
     * @return  string
     */

    public function getName()
    {
        return $this->name;
    }

    /**
     * Return icon set location.
     * 
     * @return  string
     */

    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Return the maximum width of rendered sprite image.
     * 
     * @return  int
     */

    public function getMaxWidth()
    {
        return $this->max_width;
    }

    /**
     * Set maximum width of rendered sprite image.
     * 
     * @param   int $max_width
     * @return  $this
     */

    public function setMaxWidth(int $max_width)
    {
        $this->max_width = $max_width;

        return $this;
    }

    /**
     * Return the minimum dimension of each icons.
     * 
     * @return  int
     */

    public function getMinSize()
    {
        return $this->min_size;
    }

    /**
     * Set the minimum dimension of each icons.
     * 
     * @param   int $min_size
     * @return  $this
     */

    public function setMinSize(int $min_size)
    {
        $this->min_size = $min_size;

        return $this;
    }

    /**
     * Return the maximum dimension of each icons.
     * 
     * @return  int
     */

    public function getMaxSize()
    {
        return $this->max_size;
    }

    /**
     * Set the maximum dimension of each icons.
     * 
     * @param   int $max_size
     * @return  $this
     */

    public function setMaxSize(int $max_size)
    {
        $this->max_size = $max_size;

        return $this;
    }

    /**
     * Return the rendering quality value of an image.
     * 
     * @return  int
     */

    public function getQualityIndex()
    {
        return $this->quality;
    }

    /**
     * Set the rendering quality of sprite images.
     * 
     * @param   int $quality
     * @return  $this
     */

    public function setQualityIndex(int $quality)
    {
        if($quality <= 9 && $quality >= 0)
        {
            $this->quality = $quality;
        }

        return $this;
    }

    /**
     * Return the path where to save the generated images.
     * 
     * @return  string
     */

    public function getGeneratedImagePath()
    {
        return $this->image_path;
    }

    /**
     * Set the location where to save the generated images.
     * 
     * @param   string $path
     * @return  $this
     */

    public function setGeneratedImagePath(string $path)
    {
        if(in_array(substr($path, strlen($path) - 1, strlen($path)), ['/', '\\']))
        {
            $path = substr($path, 0, strlen($path) - 1);
        }

        $this->image_path = str_replace('/', '\\', $path);

        return $this;
    }

    /**
     * Return the path where to save the generated css.
     * 
     * @return  string
     */

    public function getGeneratedCSSPath()
    {
        return $this->css_path;
    }

    /**
     * Set the location where to save the generated css.
     * 
     * @param   string $path
     * @return  $this
     */

    public function setGeneratedCSSPath(string $path)
    {
        if(in_array(substr($path, strlen($path) - 1, strlen($path)), ['/', '\\']))
        {
            $path = substr($path, 0, strlen($path) - 1);
        }

        $this->css_path = str_replace('/', '\\', $path);

        return $this;
    }

    /**
     * Set the base URL of the generated image.
     * 
     * @param   string $url
     * @return  $this
     */

    public function setImageBaseURL(string $url)
    {
        if(in_array(substr($url, strlen($url) - 1, strlen($url)), ['/', '\\']))
        {
            $url = substr($url, 0, strlen($url) - 1);
        }

        $this->base_url = $url;

        return $this;
    }

    /**
     * Return the generated image base URL.
     * 
     * @return  string
     */

    public function getImageBaseURL()
    {
        return $this->base_url;
    }

    /**
     * Set common path for generated css and image.
     * 
     * @param   string $path
     * @return  $this
     */

    public function setCommonPath(string $path)
    {
        return $this->setGeneratedImagePath($path)->setGeneratedCSSPath($path);
    }

    /**
     * Return list of images from path.
     * 
     * @return  array
     */

    public function getImages()
    {
        return $this->images;
    }

    /**
     * Generate sprite image and stylesheet.
     * 
     * @return  void
     */

    public function generate()
    {
        if(!$this->isGDEnabled())
        {
            return Console::error("GD library is currently not supported in your machine.");
        }

        if(!file_exists($this->image_path))
        {
            return Console::error("Location is missing or invalid.");
        }

        Console::log("Sprite " . static::$version);
        Console::success("Sprite generation has started...");
        Console::lineBreak();

        Console::warn("Sprite Name      :\e[39m " . $this->name);
        Console::warn("Location         :\e[39m " . $this->location);
        Console::warn("Icons Found      :\e[39m " . sizeof($this->images));
        Console::warn("Image Type       :\e[39m " . implode(", ", $this->extensions));
        Console::warn("Image Path       :\e[39m " . $this->image_path . "\sprite-" . $this->name . ".png");
        Console::warn("CSS Path         :\e[39m " . $this->css_path . "\sprite-" . $this->name . ".css");
        
        if(!empty($this->images))
        {
            $tiles              = array();
            $width              = 0;
            $height             = 0;
            $largest_height     = 0;
            $largest_heights    = array();
            $canvas_width       = 0;
            $canvas_height      = 0;
            $n                  = 0;

            foreach($this->images as $image)
            {
                $n++;

                if($image->exists() && 
                    $image->getWidth() >= $this->getMinSize() && 
                    $image->getHeight() >= $this->getMinSize() &&
                    $image->getWidth() <= $this->getMaxSize() &&
                    $image->getHeight() <= $this->getMaxSize()
                    )
                {
                    $x = $width;
                    $y = $height;
                    $z = false;

                    if($image->getHeight() > $largest_height)
                    {
                        $largest_height = $image->getHeight();
                    }

                    $tiles[] = new Tile($image, $x, $y);

                    if(($width + $image->getWidth()) > $width && ($width + $image->getWidth()) <= $this->max_width)
                    {
                        $width += $image->getWidth();
                    }

                    if($width > $canvas_width)
                    {
                        $canvas_width = $width;
                    }

                    if($n == sizeof($this->images) && !$z)
                    {
                        $z = true;
                        $largest_heights[] = $largest_height;
                    }

                    if(($width + $image->getWidth()) > $this->max_width)
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
            
            if(empty($tiles))
            {
                return Console::error("No image assets to compile.");
            }

            $canvas_height  = array_sum($largest_heights);
            $canvas         = imagecreatetruecolor($canvas_width, $canvas_height);
            $file_img       = $this->image_path . '\sprite-' . $this->name . '.png';
            $file_css       = $this->css_path . '\sprite-' . $this->name . '.css';
            $copies         = array();
            $css            = array();

            Console::warn("Canvas Width     :\e[39m " . $canvas_width . 'px');
            Console::warn("Canvas Height    :\e[39m " . $canvas_height . 'px');
            Console::lineBreak();

            if(file_exists($file_img))
            {
                Console::success("Existing sprite image has been deleted.");
                unlink($file_img);
            }

            if(file_exists($file_css))
            {
                Console::success("Existing sprite stylesheet has been deleted.");
                unlink($file_css);
            }

            imagefill($canvas, 0, 0, IMG_COLOR_TRANSPARENT);
            imagesavealpha($canvas, true);
            imagealphablending($canvas, true);

            if(!is_null($this->base_url))
            {
                $background = $this->base_url . "/sprite-" . $this->name . '.png';
            }
            else
            {
                $background = "sprite-" . $this->name . '.png';
            }

            $css[] = ".sprite-" . $this->name . "{background-image:url('$background') !important;background-color:transparent}";

            foreach($tiles as $tile)
            {
                $image = $tile->getImage();
                $resource = $tile->getResource();

                imagecopy($canvas, $resource, $tile->getX(), $tile->getY(), 0, 0, $tile->getWidth(), $tile->getHeight());

                $copies[]   = $resource;
                $styles     = ".sprite-" . $this->name . "-" . $image->getName() . "{";
                $styles    .= "background-repeat:no-repeat;";
                $styles    .= "background-position:" . ($tile->getX() * -1) . "px " . ($tile->getY() * -1) . "px !important;";
                $styles    .= "width:" . $tile->getWidth() . "px;";
                $styles    .= "height:" . $tile->getHeight() . "px";

                $css[] = $styles . "}";
            }

            Console::success("Sprite image has been generated.");
            imagepng($canvas, $file_img, $this->quality);
            imagedestroy($canvas);

            foreach($copies as $copy)
            {
                imagedestroy($copy);
            }

            $file = fopen($file_css, 'w');
            fwrite($file, implode(PHP_EOL, $css));
            fclose($file);

            Console::success("Sprite stylesheet has been generated.");
            Console::success("Sprite has been successfully generated.");
        }
        else
        {
            Console::error("No image assets to compile.");
        }
    }

    /**
     * Return the current version of sprite.
     * 
     * @return  string
     */

    public static function version()
    {
        return self::$version;
    }

}