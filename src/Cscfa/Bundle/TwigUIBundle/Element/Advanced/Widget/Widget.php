<?php
/**
 * This file is a part of CSCFA TwigUi project.
 * 
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Widget
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\TwigUIBundle\Element\Advanced\Widget;

use Cscfa\Bundle\TwigUIBundle\Element\Base\TagContainer;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\ScriptedInterface;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\StylizedInterface;
use Cscfa\Bundle\ToolboxBundle\Tool\Cache\CacheFile;
use Cscfa\Bundle\ToolboxBundle\Tool\Cache\CacheTool;

/**
 * Widget class.
 *
 * The Widget class is used
 * to create an html complex
 * displayed element.
 *
 * @category Widget
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
abstract class Widget extends TagContainer implements StylizedInterface, ScriptedInterface
{

    /**
     * Widget cache file.
     * 
     * This constant indicate
     * the widget cache file.
     * 
     * @var string
     */
    const WIDGET_CACHE_FILE = "twigBundleWidget";

    /**
     * The CacheFile.
     * 
     * This property contain
     * the CacheFile of the
     * widget.
     * 
     * @var CacheFile
     */
    protected $cacheFile;

    /**
     * Default constructor.
     * 
     * This constructor initialize
     * the properties.
     * 
     * @param CacheTool $cacheTool The cache tool service.
     */
    public function __construct(CacheTool $cacheTool)
    {
        parent::__construct();
        
        $this->cacheFile = $cacheTool->get(self::WIDGET_CACHE_FILE);
    }

    /**
     * Get cache parameter.
     * 
     * This method return a
     * cached parameter.
     * 
     * @param string $parameter The parameter to get
     * 
     * @return mixed|NULL
     */
    protected function getCache($parameter)
    {
        return $this->cacheFile->get($parameter);
    }

    /**
     * Set cache parameter.
     * 
     * This method allow
     * to set a cached 
     * parameter. and save
     * it.
     * 
     * @param string $parameter The parameter to set
     * @param mixed  $value     The value to register
     * 
     * @return Widget
     */
    protected function setCache($parameter, $value)
    {
        $this->cacheFile->set($parameter, $value)->save();
        return $this;
    }
    
    /**
     * Get cache file.
     * 
     * This method allow to
     * get the current cache 
     * file instance.
     * 
     * @return CacheFile
     */
    protected function getCacheFile()
    {
        return $this->cacheFile;
    }

    /**
     * Has style.
     * 
     * This method check if the
     * current element has style.
     * 
     * @return boolean
     */
    abstract public function hasStyle();

    /**
     * Get styles.
     * 
     * This method return all
     * of the element stylesheets.
     * 
     * @return array
     */
    abstract public function getStyles();

    /**
     * Has script link.
     *
     * This method indicate the
     * script link existance
     * of the current instance.
     *
     * @return boolean
     */
    abstract public function hasScriptLink();

    /**
     * Get script link.
     *
     * This method return all
     * of the existings script
     * links of the current
     * instance.
     *
     * @return array
     */
    abstract public function getScriptLink();

    /**
     * Get script link count.
     *
     * This method return the
     * current instance script
     * links counts.
     *
     * @return integer
     */
    abstract public function getScriptLinkCount();

    /**
     * Get custom script.
     *
     * This method indicate the
     * existance of customized
     * script.
     *
     * @return boolean
     */
    abstract public function hasCustomScript();

    /**
     * Get custom script.
     *
     * This method return the
     * current instance custom
     * script.
     *
     * @return string
     */
    abstract public function getCustomScript();

    /**
     * Get custom script length.
     *
     * This method return the
     * current instance custom
     * script length.
     *
     * @return integer
     */
    abstract public function getCustomScriptLength();
}
