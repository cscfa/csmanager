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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Search\Directory;

use Cscfa\Bundle\ToolboxBundle\Search\FileSystem\FileSystemElement;

/**
 * DirectoryScan class.
 *
 * The DirectoryScan class is an utility
 * tool to reflect a directory.
 *
 * @category Exception
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class DirectoryScan
{
    /**
     * The directories.
     *
     * The directory array
     * that is register.
     *
     * @var array
     */
    protected $directories;

    /**
     * The files.
     *
     * The file array that
     * is register.
     *
     * @var array
     */
    protected $files;

    /**
     * Default constructor.
     *
     * DirectoryScan constructor that
     * register as optionnal a directories
     * array and files array.
     *
     * @param array $directories The directory array to register
     * @param array $files       The file array to register
     */
    public function __construct(array $directories = array(), array $files = array())
    {
        $this->directories = $directories;
        $this->files = $files;
    }

    /**
     * Add FileSystemElement.
     *
     * This method allow to
     * register a new FileSystemElement
     * into the current DirectoryScan
     * as file or directory. Return
     * false if the FileSystemElement
     * is not file or directory.
     *
     * @param FileSystemElement $element The FileSystemElement to register
     *
     * @return bool
     */
    public function addFilesystem(FileSystemElement $element)
    {
        if ($element->isDir()) {
            $this->directories[] = $element;
        } elseif ($element->isFile()) {
            $this->files[] = $element;
        } else {
            return false;
        }

        return true;
    }

    /**
     * Get directories.
     *
     * Get the array of directories
     * registered into the
     * current DirectoryScan.
     *
     * @return array
     */
    public function getDirectories()
    {
        return $this->directories;
    }

    /**
     * Get files.
     *
     * Get the array of files
     * registered into the
     * current DirectoryScan.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Has file.
     *
     * Return true if the current
     * DirectoryScan register at least
     * one file.
     *
     * @return bool
     */
    public function hasFile()
    {
        return !empty($this->files);
    }

    /**
     * Has directory.
     *
     * Return true if the current
     * DirectoryScan register at least
     * one directory.
     *
     * @return bool
     */
    public function hasDirectory()
    {
        return !empty($this->directories);
    }

    /**
     * Merge a DirectoryScan.
     *
     * This method merge a DirectoryScan
     * with the current instance.
     *
     * @param DirectoryScan $directoryScan The directoryScan to merge
     */
    public function merge(DirectoryScan $directoryScan)
    {
        $this->directories = array_merge($this->directories, $directoryScan->getDirectories());
        $this->files = array_merge($this->files, $directoryScan->getFiles());
    }
}
