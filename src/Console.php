<?php

namespace Sprite;

class Console
{
    /**
     * Different types of console messages.
     * 
     * @var array
     */

    private $types = [
        'log',
        'error',
        'warn',
        'success',
    ];

    /**
     * What type of console message to display.
     * 
     * @var string
     */

    private $type;

    /**
     * Message to display in console.
     * 
     * @var string
     */

    private $text;

    /**
     * Create a new console message.
     * 
     * @param   string $type
     * @param   string $text
     * @return  void
     */

    public function __construct(string $type, string $text)
    {
        $this->type = strtolower($type);
        $this->text = $text;
    }

    /**
     * Return console message.
     * 
     * @return  string
     */

    public function getText()
    {
        return $this->text;
    }

    /**
     * Display new console message.
     * 
     * @return  \Sprite\Console
     */

    public function display()
    {
        $type = $this->type;

        // Check if console message type was supported.
        if(in_array($type, $this->types))
        {
            if($type == "error")
            {
                echo "\e[31m" . $this->text;
            }
            else if($type == "warn")
            {
                echo "\e[33m" . $this->text;
            }
            else if($type == "success")
            {
                echo "\e[32m" . $this->text;
            }
            else
            {
                echo "\e[97m" . $this->text;
            }

            // Reset the text color to white then add line break.
            echo "\e[97m\n";
        }

        return $this;
    }

    /**
     * Log a new console message.
     * 
     * @param   string $text
     * @return  \Sprite\Console
     */

    public static function log(string $text)
    {
        $instance = new self('log', $text);
    
        return $instance->display();
    }

    /**
     * Display a new error message to the console.
     * 
     * @param   string $text
     * @return  \Sprite\Console
     */

    public static function error(string $text)
    {
        $instance = new self('error', $text);
    
        return $instance->display();
    }

    /**
     * Display warning messages to the console.
     * 
     * @param   string $text
     * @return  \Sprite\Console
     */

    public static function warn(string $text)
    {
        $instance = new self('warn', $text);
    
        return $instance->display();
    }

    /**
     * Display success message to the console.
     * 
     * @param   string $text
     * @return  \Sprite\Console
     */

    public static function success(string $text)
    {
        $instance = new self('success', $text);
    
        return $instance->display();
    }

    /**
     * Display a new console line break.
     * 
     * @return  \Sprite\Console
     */

    public static function lineBreak()
    {
        $instance = new self('log', ' ');
    
        return $instance->display();
    }

}