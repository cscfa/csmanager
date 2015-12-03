<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Set
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Tool\Cache;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * CacheTool class.
 *
 * The CacheTool class is used to 
 * manage CSCFA cache operations.
 *
 * @category Cache
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CacheTool
{

    /**
     * A file error constant.
     * 
     * This constant represent 
     * that a file doesn't
     * exist.
     * 
     * @var integer
     */
    const FILE_UNEXIST = 1;

    /**
     * A file error constant.
     * 
     * This constant represent 
     * that a file doesn't
     * be read.
     * 
     * @var integer
     */
    const FILE_NOT_READABLE = 2;

    /**
     * The cache directory.
     * 
     * This property indicate
     * the current cache directory
     * where the parameters files
     * will be write or read.
     * 
     * @var string
     */
    protected $kernelCacheDir;

    /**
     * Default constructor.
     * 
     * This constructor initialize
     * the properties.
     * 
     * @param string $kernelRootDir The current cache directory.
     * @param string $environment   The current application environment.
     */
    public function __construct($kernelRootDir, $environment)
    {
        $this->setKernelCacheDir($kernelRootDir, $environment);
    }

    /**
     * Get.
     * 
     * This method return a
     * CacheFile instance that
     * contain the saved 
     * parameters.
     * 
     * @param string $file The file name without extension
     * 
     * @throws FileException If the file can't be read or write
     * @return CacheFile
     */
    public function get($file)
    {
        $cachedFile = $this->loadFile($file);
        
        if($cachedFile instanceof CacheFile){
            return $cachedFile;
        } else if ($cachedFile === self::FILE_UNEXIST) {
            $cachedFile = new CacheFile($file, $this);
            $writeState = $this->set($file, $cachedFile);
            
            if($writeState === false){
                throw new FileException(sprintf("Fail to write %s cache file", $file), 500);
            } else {
                return $cachedFile;
            }
        } else {
            throw new FileException(sprintf("Fail to read %s cache file", $file), 500);
        }
    }

    /**
     * Set.
     * 
     * This method allow to set
     * a file content.
     * 
     * @param string    $file  The file name without extension
     * @param CacheFile $value The CacheFile to write
     * 
     * @return integer|false
     */
    public function set($file, CacheFile $value)
    {
        return file_put_contents($this->compileFileName($file), $value->serialize());
    }

    /**
     * Load file.
     * 
     * This method return a
     * CacheFile instance or
     * an error value represented
     * as the current class
     * constant.
     * 
     * @param string $file The file to load without extension
     * 
     * @return integer|CacheFile
     */
    protected function loadFile($file)
    {
        $filePath = $this->compileFileName($file);
        
        if (! is_file($filePath)) {
            return self::FILE_UNEXIST;
        } else if (! is_readable($filePath)) {
            return self::FILE_NOT_READABLE;
        } else {
            return new CacheFile($file, $this, file_get_contents($filePath));
        }
    }

    /**
     * Get kernel cache dir.
     * 
     * This method return the 
     * current cache directory
     * where the parameters files
     * will be write or read.
     * 
     * @return string
     */
    public function getKernelCacheDir()
    {
        return $this->kernelCacheDir;
    }

    /**
     * Set kernel cache dir.
     * 
     * This method allow to set
     * the current cache directory
     * where the parameters files
     * will be write or read.
     * 
     * @param string $kernelRootDir The current application kernel root dir
     * @param string $environment   The current application environment
     * 
     * @return CacheTool
     */
    protected function setKernelCacheDir($kernelRootDir, $environment)
    {
        if (! is_dir($kernelRootDir . "/cache/$environment/cscfa")) {
            mkdir($kernelRootDir . "/cache/$environment/cscfa");
        }
        
        $this->kernelCacheDir = $kernelRootDir . "/cache/$environment/cscfa";
        return $this;
    }

    /**
     * Compile file name.
     * 
     * This method return the
     * compiled file name.
     * 
     * @param string $file The file name without extension
     * @return string
     */
    protected function compileFileName($file)
    {
        return $this->kernelCacheDir . '/' . $file . '.pser';
    }
}
