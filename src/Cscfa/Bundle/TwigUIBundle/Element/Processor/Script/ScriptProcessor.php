<?php
/**
 * This file is a part of CSCFA TwigUi project.
 * 
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Processor
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\TwigUIBundle\Element\Processor\Script;

use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\SubTagedInterface;
use Cscfa\Bundle\TwigUIBundle\Element\Twig\JavaScript\TwigJavascript;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\ScriptedInterface;

/**
 * ScriptProcessor class.
 *
 * The ScriptProcessor class
 * is used to search and
 * assign scripts.
 *
 * @category Processor
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ScriptProcessor
{

    /**
     * Index constant.
     * 
     * This constant point
     * on link index of
     * array properties.
     * 
     * @var string
     */
    const INDEX_LINK = "link";

    /**
     * Index constant.
     * 
     * This constant point
     * on text index of
     * array properties.
     * 
     * @var string
     */
    const INDEX_TEXT = "text";

    /**
     * Skip.
     * 
     * This property indicate
     * all of the scripts to
     * skip.
     * 
     * It's a two dimension
     * associative array that
     * contain in two index
     * the links and the text 
     * scripts to skip.
     * 
     * @var array
     */
    protected $skip;

    /**
     * Added.
     *
     * This property indicate
     * all of the scripts to
     * add.
     *
     * It's a two dimension
     * associative array that
     * contain in two index
     * the links and the text
     * scripts to add.
     *
     * @var array
     */
    protected $added;

    /**
     * Default constructor.
     * 
     * This constructor initialize
     * the properties.
     */
    public function __construct()
    {
        foreach (array(
            "skip",
            "added"
        ) as $property) {
            $this->$property = array(
                self::INDEX_LINK => array(),
                self::INDEX_TEXT => array()
            );
        }
    }

    /**
     * Add skip link.
     * 
     * This method insert
     * a new link to skip.
     * 
     * @param script $link The link to skip.
     * 
     * @return ScriptProcessor
     */
    public function addSkipLink($link)
    {
        if (! in_array($link, $this->skip[self::INDEX_LINK])) {
            $this->skip[self::INDEX_LINK][] = $link;
        }
        
        return $this;
    }

    /**
     * Set skip link.
     * 
     * This method allow to
     * set the skip link
     * array.
     * 
     * @param array $link The array that conatin all of the links to skip.
     * 
     * @return ScriptProcessor
     */
    public function setSkipLink(array $link)
    {
        $this->skip[self::INDEX_LINK] = $link;
        
        return $this;
    }

    /**
     * Get skip link.
     * 
     * This method return the
     * array of links to skip.
     * 
     * @return array
     */
    public function getSkipLink()
    {
        return $this->skip[self::INDEX_LINK];
    }

    /**
     * Add skip text.
     * 
     * This method register
     * a new text script to
     * skip.
     * 
     * @param string $script The text script to skip.
     * 
     * @return ScriptProcessor
     */
    public function addSkipText($script)
    {
        if (! in_array($script, $this->skip[self::INDEX_TEXT])) {
            $this->skip[self::INDEX_TEXT][] = $script;
        }
        
        return $this;
    }

    /**
     * Set skip text.
     * 
     * This method allow to
     * set the array of text
     * script to skip.
     * 
     * @param array $script The text script array to skip.
     * 
     * @return ScriptProcessor
     */
    public function setSkipText(array $script)
    {
        $this->skip[self::INDEX_TEXT] = $script;
        
        return $this;
    }

    /**
     * Get skip text.
     * 
     * This method return
     * all of the text script
     * to skip into an array.
     * 
     * @return array
     */
    public function getSkipText()
    {
        return $this->skip[self::INDEX_TEXT];
    }

    /**
     * Add link.
     * 
     * This method register
     * a new linked script to 
     * register.
     * 
     * @param string $link The script link to register.
     * 
     * @return ScriptProcessor
     */
    public function addLink($link)
    {
        if (! in_array($link, $this->added[self::INDEX_LINK])) {
            $this->added[self::INDEX_LINK][] = $link;
        }
        
        return $this;
    }

    /**
     * Set link.
     * 
     * This method allow to set
     * the array of script link
     * to register.
     * 
     * @param array $link The script link array to register.
     * 
     * @return ScriptProcessor
     */
    public function setLink(array $link)
    {
        $this->added[self::INDEX_LINK] = $link;
        
        return $this;
    }

    /**
     * Get link.
     * 
     * This method return all
     * of the script links
     * that will be added.
     * 
     * @return array
     */
    public function getLink()
    {
        return $this->added[self::INDEX_LINK];
    }

    /**
     * Add script.
     * 
     * This method register
     * a new text script to 
     * add.
     * 
     * @param string $script The script to add.
     * 
     * @return ScriptProcessor
     */
    public function addScript($script)
    {
        if (! in_array($script, $this->added[self::INDEX_TEXT])) {
            $this->added[self::INDEX_TEXT][] = $script;
        }
        
        return $this;
    }

    /**
     * Set script.
     * 
     * This method allow to
     * set the text script
     * array to add.
     * 
     * @param array $script The text script array to add.
     * 
     * @return ScriptProcessor
     */
    public function setScript(array $script)
    {
        $this->added[self::INDEX_TEXT] = $script;
        
        return $this;
    }

    /**
     * Get script.
     * 
     * This method return
     * all of the text script
     * to add into an array.
     * 
     * @return array
     */
    public function getScript()
    {
        return $this->added[self::INDEX_TEXT];
    }

    /**
     * Process.
     * 
     * This method proceed to
     * a whole search on a 
     * SubTagedInterface and
     * return a TwigJavascript
     * element.
     * 
     * @param SubTagedInterface $tagContainer The SubTagedInterface whence extract the scripts.
     * 
     * @return TwigJavascript
     */
    public function process(SubTagedInterface $tagContainer)
    {
        $javascript = new TwigJavascript();
        
        list ($links, $scripts) = $this->search($tagContainer);
        
        $links = array_unique(array_merge($this->getLink(), $links));
        $scripts = array_unique(array_merge($this->getScript(), $scripts));
        
        $this->assign($links, $scripts, $javascript);
        
        return $javascript;
    }

    /**
     * Search.
     * 
     * This method return an
     * array that contain as
     * sub array respectively
     * the linked scripts and
     * the text scripts.
     * 
     * @param SubTagedInterface $tagContainer The SubTageInterface whence extract the scripts.
     * 
     * @return array
     */
    protected function search(SubTagedInterface $tagContainer)
    {
        $tags = $tagContainer->getSubTags();
        $tags[] = $tagContainer;
        $links = array();
        $scripts = array();
        
        foreach ($tags as $tag) {
            if ($tag instanceof ScriptedInterface) {
                if ($tag->hasScriptLink()) {
                    foreach ($tag->getScriptLink() as $link) {
                        $links[] = $link;
                    }
                }
                
                if ($tag->hasCustomScript()) {
                    foreach ($tag->getCustomScript() as $script) {
                        $scripts[] = $script;
                    }
                }
            }
        }
        
        $links = array_diff(array_unique($links), $this->getSkipLink());
        $scripts = array_diff(array_unique($scripts), $this->getSkipText());
        
        return array(
            $links,
            $scripts
        );
    }

    /**
     * Assign.
     * 
     * This method assign the
     * exported scripts to the
     * TwigJavascript element.
     * 
     * @param array          $links       The array of linked scripts to assign
     * @param array          $scripts     The array of text scripts to assign
     * @param TwigJavascript &$javascript The TwigJavascript element where assign the scripts
     * 
     * @return void
     */
    protected function assign(array $links, array $scripts, TwigJavascript &$javascript)
    {
        foreach ($links as $link) {
            $javascript->addScriptLinked($link);
        }
        foreach ($scripts as $script) {
            $javascript->addScriptText($script);
        }
    }
}
