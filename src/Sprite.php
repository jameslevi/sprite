<?php

namespace Sprite;

class Sprite
{
    /**
     * Current sprite api version.
     * 
     * @var string
     */

    private $version = '1.0.7';

    /**
     * Contains the current instance of this class.
     * 
     * @var \Sprite\Sprite
     */

    private static $instance;

    /**
     * Contains an array of inputs from the CLI.
     * 
     * @var array
     */

    private $argv;

    /**
     * Contains the root directory of the application.
     * 
     * @var array
     */

    private $root;

    /**
     * Check if program is still not executed.
     * 
     * @var bool
     */

    private $executed = false;

    /**
     * Check if program has ended.
     * 
     * @var bool
     */

    private $ended = false;

    /**
     * Store sprite configurations.
     * 
     * @var array
     */

    private $config;

    /**
     * Create new instance of this class.
     * 
     * @param   array $argv
     * @param   string $root
     * @return  void
     */

    private function __construct(array $argv, string $root)
    {
        array_shift($argv);

        $this->argv = $argv;
        $this->root = $root;

        // Test if PHP image GD library was disabled then terminate.
        if(!$this->isGDEnabled())
        {
            Console::error('GD image library was disabled.');
            $this->executed = true;
            $this->terminate();
        }

        // Load configurations before executing.
        $this->config = $this->loadConfig();

        // Check if config was successfully loaded.
        if(is_null($this->config))
        {
            Console::error('The sprite.json file was missing from the root directory.');
        }
    }

    /**
     * Return current api version.
     * 
     * @return  string
     */

    public function version()
    {
        return $this->version;
    }

    /**
     * Check if image GD library was enabled.
     * 
     * @return  bool
     */

    public function isGDEnabled()
    {
        return extension_loaded('gd');
    }

    /**
     * Return the root directory of the application.
     * 
     * @return  string
     */

    public function root()
    {
        return $this->root;
    }

    /**
     * Load configurations from sprite.json file.
     * 
     * @return  array
     */
    
    private function loadConfig()
    {
        $config = $this->root . '\sprite.json';
        
        if(file_exists($config))
        {
            return json_decode(file_get_contents($config), true);
        }
    }

    /**
     * Return config properties by key.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function config(string $key = null)
    {
        if(!is_null($key) && array_key_exists($key, $this->config))
        {
            return $this->config[$key];
        }
        else
        {
            return $this->config;
        }
    }

    /**
     * Return sprite settings.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function settings(string $key)
    {
        return $this->config('settings')[$key];
    }

    /**
     * Execute the input CLI commands.
     * 
     * @return  \Sprite\Sprite
     */

    public function exec()
    {
        if(!$this->executed)
        {
            $this->executed = true;
            $this->runtime();
        }

        return $this;
    }

    /**
     * Everything will happen here.
     * 
     * @return  void
     */

    private function runtime()
    {
        if(!empty($this->argv))
        {
            $directive = strtolower($this->argv[0]);
            $value = $this->argv[1] ?? null;

            // Return current sprite api version.
            if($directive === '-v' || $directive === '--version')
            {
                Console::warn('SPRITE v' . $this->version);
            }

            // Show sprite CLI command list.
            else if($directive === '-h' || $directive === '--help')
            {
                Console::warn('SPRITE v' . $this->version);
                Console::log('Is an image compiler written in PHP created for web developers.');
                Console::lineBreak();
                Console::warn('Usage:');
                Console::log('    php sprite [command] [value]');
                Console::lineBreak();
                Console::warn('Options:');
                Console::success('    -h, --help               - Display sprite CLI command list.');
                Console::success('    -v, --version            - Display current api version.');
                Console::success('    -c, --clear              - Clear the CLI screen.');
                Console::success('    -x, --delete             - Delete the current sprite build.');
                Console::success('    -g, --generate           - Generate new sprite build.');
            }

            // Generate new sprite build.
            else if($directive === '-g' || $directive === '--generate')
            {
                Builder::init($this);
                Console::success('Done generating sprites.');
            }

            // Clear CLI screen.
            else if($directive === '-c' || $directive === '--clear')
            {
                print("\033[2J\033[;H");
            }

            // Delete the current sprite build.
            else if($directive === '-x' || $directive === '--delete')
            {
                $path = $this->root . str_replace('/', '\\', $this->config('path'));
                $files = array_diff(scandir($path), ['.', '..']);

                if(file_exists($path))
                {
                    foreach($files as $file)
                    {
                        $location = $path . '\\' . $file;

                        // Test if file exist and writable.
                        if(file_exists($location) && is_writable($location))
                        {
                            unlink($location);
                        }
                    }

                    Console::success('Sprite cache was successfully cleared.');
                }
                else
                {
                    Console::error('Sprite directory is missing.');
                }
            }

            else
            {
                Console::error('Unknown sprite command.');
            }
        }
        else
        {
            Console::error('Unknown sprite command.');
        }
    }

    /**
     * Terminate sprite program.
     * 
     * @return  void
     */

    public function terminate()
    {
        if(!$this->ended && $this->executed)
        {
            $this->ended = true;
            exit(0);
        }
    }

    /**
     * Instantiate new sprite instance.
     * 
     * @param   array $argv
     * @param   string $root
     * @return  \Sprite\Sprite
     */

    public static function init(array $argv, string $root)
    {
        if(is_null(static::$instance))
        {
            static::$instance = new self($argv, $root);
        }

        return static::$instance;
    }

    /**
     * Return the sprite object.
     * 
     * @return  \Sprite\Sprite
     */

    public static function context()
    {
        return static::$instance;
    }

}