<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Exception
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Search\Directory;

use Cscfa\Bundle\ToolboxBundle\Exception\Directory\DirectoryException;
use Cscfa\Bundle\ToolboxBundle\Exception\Directory\DirectoryReadableException;
use Cscfa\Bundle\ToolboxBundle\Exception\Directory\NotDirectoryException;
use Cscfa\Bundle\ToolboxBundle\Search\FileSystem\FileSystemElement;
use Cscfa\Bundle\ToolboxBundle\Exception\Type\UnexpectedTypeException;

/**
 * DirectorySearchTool class.
 *
 * The DirectorySearchTool class is an utility
 * tool to inspect a directory.
 *
 * @category Exception
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class DirectorySearchTool
{
    /**
     * The directory.
     *
     * This parameter register
     * the directory to
     * search.
     *
     * @var \Directory
     */
    protected $directory;

    /**
     * Default constructor.
     *
     * The default DirectorySearchTool
     * constructor. The $directory
     * location is an optionnal
     * parameter.
     *
     * @param string $directory The directory location
     *
     * @throws DirectoryException if an error occurred. The appointed exception will be inserted as previous.
     */
    public function __construct($directory = null)
    {
        if ($directory !== null) {
            try {
                $this->setDir($directory);
            } catch (DirectoryException $e) {
                throw new DirectoryException('An error occurred during the directory setting', 500, $e);
            }
        }
    }

    /**
     * Set the directory.
     *
     * This method allow to
     * set the current directory.
     *
     * @param string $directory The directory emplacement
     *
     * @throws DirectoryException         if the filesystem element does not exist
     * @throws DirectoryReadableException if the directory can't be read
     * @throws NotDirectoryException      if the filesystem element appoint by the $directory parameter exist
     *                                    but is not a directory
     */
    public function setDir($directory)
    {
        if ($this->directory instanceof \Directory) {
            $this->directory->close();
        }

        if (!file_exists($directory)) {
            throw new DirectoryException(sprintf('The %s directory does not exist', $directory), 404);
        } elseif (!is_readable($directory)) {
            throw new DirectoryReadableException(sprintf('The %s directory can not be read', $directory), 403);
        }

        if (is_dir($directory)) {
            $this->directory = dir($directory);
        } else {
            throw new NotDirectoryException(sprintf('The %s filesystem element is not a directory', $directory), 415);
        }
    }

    /**
     * Search directories.
     *
     * This method allow to search each
     * directories matching a pattern into the
     * current directory or recursively from
     * the current directory.
     *
     * @param string $directoriesPattern The complete path pattern
     * @param string $recursive          The recursive state
     * @param string $asRoot             The base path to remove from the filename
     *
     * @throws UnexpectedTypeException
     *
     * @return array
     */
    public function searchDirectories($directoriesPattern, $recursive = false, $asRoot = '')
    {
        if ($asRoot !== '') {
            $asRoot = realpath($asRoot).'/';
        }

        try {
            $scanResult = $this->scanDirectory($this->directory, $recursive);
        } catch (UnexpectedTypeException $e) {
            throw new UnexpectedTypeException('Type error', 500, $e);
        }

        $directoriesMatches = array();

        if (!$scanResult->hasDirectory()) {
            return array();
        } else {
            foreach ($scanResult->getDirectories() as $fileSystem) {
                if ($fileSystem instanceof FileSystemElement) {
                    if (preg_match($directoriesPattern, $fileSystem->getEmplacement())) {
                        $completePath = realpath($fileSystem->getEmplacement());
                        $directoriesMatches[] = substr($completePath, strlen($asRoot));
                    }
                } else {
                    $fsElementClass = "Cscfa\Bundle\ToolboxBundle\Search\FileSystem\FileSystemElement";
                    throw new UnexpectedTypeException(
                        sprintf(
                            '%s class excpected but get %s class',
                            $fsElementClass,
                            get_class($fileSystem)
                        ),
                        500
                    );
                }
            }
        }

        return $directoriesMatches;
    }

    /**
     * Search filename.
     *
     * This method allow to search each
     * filename matching a pattern into the
     * current directory or recursively from
     * the current directory.
     *
     * @param string $filePattern The file pattern to match
     * @param bool   $recursive   The recursive state
     * @param string $asRoot      The base path to remove from the filename
     *
     * @throws UnexpectedTypeException
     *
     * @return array
     */
    public function searchFilename($filePattern, $recursive = false, $asRoot = '')
    {
        if ($asRoot !== '') {
            $asRoot = realpath($asRoot).'/';
        }

        try {
            $scanResult = $this->scanDirectory($this->directory, $recursive);
        } catch (UnexpectedTypeException $e) {
            throw new UnexpectedTypeException('Type error', 500, $e);
        }

        $fileMatches = array();

        if (!$scanResult->hasFile()) {
            return array();
        } else {
            foreach ($scanResult->getFiles() as $fileSystem) {
                if ($fileSystem instanceof FileSystemElement) {
                    if (preg_match($filePattern, $fileSystem->getBasename())) {
                        $completePath = realpath($fileSystem->getEmplacement());
                        $fileMatches[] = substr($completePath, strlen($asRoot));
                    }
                } else {
                    $fsElementClass = "Cscfa\Bundle\ToolboxBundle\Search\FileSystem\FileSystemElement";
                    throw new UnexpectedTypeException(
                        sprintf(
                            '%s class excpected but get %s class',
                            $fsElementClass,
                            get_class($fileSystem)
                        ),
                        500
                    );
                }
            }
        }

        return $fileMatches;
    }

    /**
     * Scan directory.
     *
     * This method allow to get all
     * filesystem elements from the
     * given directory.
     *
     * @param \Directory $directory The Directory to read
     * @param bool       $recursive The recursive state
     *
     * @throws UnexpectedTypeException
     *
     * @return DirectoryScan
     */
    protected function scanDirectory(\Directory $directory, $recursive)
    {
        $filesystems = $this->getFilesystemElement($directory);

        $scan = new DirectoryScan();

        foreach ($filesystems as $filesystem) {
            $scan->addFilesystem($filesystem);
        }

        if ($recursive && $scan->hasDirectory()) {
            foreach ($scan->getDirectories() as $inDir) {
                if ($inDir instanceof FileSystemElement) {
                    $dir = dir($inDir->getEmplacement());
                    $scan->merge($this->scanDirectory($dir, true));
                    $dir->close();
                } else {
                    $fsElementClass = "Cscfa\Bundle\ToolboxBundle\Search\FileSystem\FileSystemElement";
                    throw new UnexpectedTypeException(
                        sprintf('%s class excpected but get %s class', $fsElementClass, get_class($inDir)),
                        500
                    );
                }
            }
        }

        return $scan;
    }

    /**
     * Get filesystem element.
     *
     * This method allow to get all of
     * the filesystems elements from
     * a directory.
     *
     * @param \Directory $directory The Directory to read
     *
     * @return array
     */
    protected function getFilesystemElement(\Directory $directory)
    {
        $directory->rewind();

        $elements = array();

        while (false !== ($elm = $directory->read())) {
            if ($elm !== '.' && $elm !== '..') {
                $elements[] = new FileSystemElement(realpath($directory->path.'/'.$elm));
            }
        }

        return $elements;
    }

    /**
     * Close.
     *
     * This method close the
     * current instance directory.
     */
    public function close()
    {
        if ($this->directory !== null) {
            $this->directory->close();
        }
    }

    /**
     * Default desctructor.
     *
     * This destructor remove
     * the properties.
     */
    public function __destruct()
    {
        $this->close();
    }
}
