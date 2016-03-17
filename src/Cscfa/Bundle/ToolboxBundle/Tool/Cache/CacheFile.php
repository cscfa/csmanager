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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Tool\Cache;

/**
 * CacheFile class.
 *
 * The CacheFile class is used to
 * manage CSCFA cache files.
 *
 * @category Cache
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class CacheFile implements \Serializable
{
    /**
     * The parameters.
     *
     * This property contain
     * an associative array
     * to store the parameters
     * to cache.
     *
     * @var array
     */
    protected $parameters;

    /**
     * The CacheTool service.
     *
     * This property store
     * the CacheTool service.
     *
     * @var CacheTool
     */
    protected $cacheTool;

    /**
     * The filename.
     *
     * This property indicate
     * the filename without
     * extension.
     *
     * @var string
     */
    protected $file;

    /**
     * The modified state.
     *
     * This property indicate
     * that the current instance
     * was modified.
     *
     * @var bool
     */
    protected $modified;

    /**
     * Default constructor.
     *
     * This constructor initialize
     * the properties.
     *
     * @param string    $file      The filename without extension
     * @param CacheTool $cacheTool The CacheTool service
     * @param string    $data      The data to store
     */
    public function __construct($file, CacheTool &$cacheTool, $data = null)
    {
        $this->file = $file;
        $this->cacheTool = $cacheTool;
        $this->modified = false;

        if ($data === null) {
            $this->parameters = array();
        } else {
            $this->unserialize($data);
        }
    }

    /**
     * Serialize.
     *
     * This method return the
     * current serialized instance.
     *
     * @see    Serializable::serialize()
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->parameters);
    }

    /**
     * Unserialize.
     *
     * This method unserialize
     * an instance and store
     * the result.
     *
     * @see Serializable::unserialize()
     *
     * @return CacheFile
     */
    public function unserialize($data)
    {
        $this->parameters = unserialize($data);

        return $this;
    }

    /**
     * Is modified.
     *
     * This method return
     * the current modified
     * state.
     *
     * @return bool
     */
    public function isModified()
    {
        return $this->modified;
    }

    /**
     * Has parameter.
     *
     * This method result indicate
     * that a specified parameter
     * already exist.
     *
     * @param string $parameter The parameter
     *
     * @return bool
     */
    public function has($parameter)
    {
        return array_key_exists($parameter, $this->parameters);
    }

    /**
     * Has value.
     *
     * This method indicate
     * that a value already
     * exist.
     *
     * @param mixed $value The value to search
     *
     * @return bool
     */
    public function hasValue($value)
    {
        return in_array($value, $this->parameters);
    }

    /**
     * Get parameter.
     *
     * This method
     *
     * @param string $parameter The parameter to get
     *
     * @return mixed|null
     */
    public function get($parameter)
    {
        if ($this->has($parameter)) {
            return $this->parameters[$parameter];
        } else {
            return;
        }
    }

    /**
     * Set parameter.
     *
     * This method allow to
     * set a parameter value.
     *
     * @param string $parameter The parameter to set
     * @param mixed  $value     The value to store
     *
     * @return CacheFile
     */
    public function set($parameter, $value)
    {
        if ($this->has($parameter) && $this->parameters[$parameter] === $value) {
            return $this;
        }

        $this->modified = true;
        $this->parameters[$parameter] = $value;

        return $this;
    }

    /**
     * Save.
     *
     * This method allow
     * to save the current
     * instance state into
     * the cache directory.
     *
     * @return CacheFile
     */
    public function save()
    {
        if ($this->modified) {
            $this->cacheTool->set($this->file, $this);
        }

        return $this;
    }
}
