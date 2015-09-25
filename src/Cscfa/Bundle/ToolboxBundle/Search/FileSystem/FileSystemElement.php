<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Exception
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Search\FileSystem;

/**
 * FileSystemElement class.
 *
 * The FileSystemElement class is an utility
 * tool to represent a file system directory
 * or file.
 *
 * @category Exception
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class FileSystemElement
{
    /**
     * The element emplacement.
     * 
     * The element complete
     * path.
     * 
     * @var string
     */
    protected $emplacement;
    
    /**
     * The info of the path.
     * 
     * This is an array that
     * contain the result of
     * pathinfo() function.
     * 
     * @var array
     */
    protected $info;
    
    /**
     * Default constructor.
     * 
     * The FileSystemElement default
     * constructor.
     * 
     * @param string $emplacement the filesystem element emplacement
     */
    public function __construct($emplacement)
    {
        $this->emplacement = $emplacement;
        
        $defaultInfo = array(
            'dirname'=>null,
            'basename'=>null,
            'extension'=>null,
            'filename'=>null
        );
        
        if ($this->isDir() || $this->isFile()) {
            $this->info = array_merge($defaultInfo, pathinfo($emplacement));
        } else {
            $this->info = $defaultInfo;
        }
    }
    
    /**
     * Is directory.
     * 
     * Check if the filesystem
     * element is a directory.
     * 
     * @return boolean
     */
    public function isDir()
    {
        return is_dir($this->emplacement);
    }
    
    /**
     * Is file.
     * 
     * Check if the filesystem
     * element is a file.
     * 
     * @return boolean
     */
    public function isFile()
    {
        return is_file($this->emplacement);
    }
    
    /**
     * Is redable.
     * 
     * Check if the filesystem
     * element is redable.
     * 
     * @return boolean
     */
    public function isReadable()
    {
        return is_readable($this->emplacement);
    }
    
    /**
     * Is writable.
     * 
     * Check if the filesystem
     * element is writable.
     * 
     * @return boolean
     */
    public function isWritable()
    {
        return is_writable($this->emplacement);
    }
    
    /**
     * Get dirname.
     * 
     * Get the dirname or
     * null.
     * 
     * @example return '/www/htdocs/inc' for '/www/htdocs/inc/lib.inc.php'
     * @return  string
     */
    public function getDirname()
    {
        return $this->info['dirname'];
    }
    
    /**
     * Get basename.
     * 
     * Get the basename or
     * null.
     * 
     * @example return 'lib.inc.php' for '/www/htdocs/inc/lib.inc.php'
     * @return  string
     */
    public function getBasename()
    {
        return $this->info['basename'];
    }
    
    /**
     * Get extension.
     * 
     * Get the extension or
     * null.
     * 
     * @example return 'php' for '/www/htdocs/inc/lib.inc.php'
     * @return  string
     */
    public function getExtension()
    {
        return $this->info['extension'];
    }
    
    /**
     * Get filename.
     * 
     * Get the filename or
     * null.
     * 
     * @example return 'lib.inc' for '/www/htdocs/inc/lib.inc.php'
     * @return  string
     */
    public function getFilename()
    {
        return $this->info['filename'];
    }
    
    /**
     * Get the emplacement.
     * 
     * Get the current filesystem
     * element complete path.
     * 
     * @return string
     */
    public function getEmplacement()
    {
        return $this->emplacement;
    }
    
    /**
     * Get pathinfo.
     * 
     * Get the array result
     * of pathinfo() function.
     * 
     * @return array
     */
    public function getPathInfo()
    {
        return $this->info;
    }
}
