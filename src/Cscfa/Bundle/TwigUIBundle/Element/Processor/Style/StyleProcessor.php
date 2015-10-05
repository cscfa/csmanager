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
namespace Cscfa\Bundle\TwigUIBundle\Element\Processor\Style;

use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\SubTagedInterface;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\StylizedInterface;
use Cscfa\Bundle\TwigUIBundle\Element\Twig\StyleSheet\TwigStylesheet;

/**
 * StyleProcessor class.
 *
 * The StyleProcessor class
 * is used to search and
 * assign stylesheets.
 *
 * @category Processor
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class StyleProcessor
{

    /**
     * The skip array.
     * 
     * This property design the
     * stylesheets to not register.
     * 
     * @var array
     */
    protected $skip;

    /**
     * The added array.
     * 
     * This property desing the
     * stylesheets to register
     * in addition with the
     * collected stylesheets.
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
        $this->skip = array();
        $this->added = array();
    }

    /**
     * Add skip.
     * 
     * This method allow to
     * insert a new stylesheet
     * to skip.
     * 
     * @param string $skip The stylesheet to skip.
     * 
     * @return StyleProcessor
     */
    public function addSkip($skip)
    {
        $this->skip[] = $skip;
        $this->skip = array_unique($this->skip);
        
        return $this;
    }

    /**
     * Get skip.
     * 
     * This method return all
     * of skipped stylesheet
     * into an array.
     * 
     * @return array
     */
    public function getSkip()
    {
        return $this->skip;
    }

    /**
     * Set skip.
     * 
     * This method allow to
     * set the array of 
     * skipped stylesheet.
     * 
     * @param array $skip The skip array
     * 
     * @return StyleProcessor
     */
    public function setSkip(array $skip)
    {
        $this->skip = $skip;
        return $this;
    }

    /**
     * Add stylesheet.
     * 
     * This method allow to
     * insert a new stylesheet
     * to register.
     * 
     * @param string $stylesheet The stylesheet to register.
     * 
     * @return StyleProcessor
     */
    public function addStylesheet($stylesheet)
    {
        $this->added[] = $stylesheet;
        $this->added = array_unique($this->added);
        
        return $this;
    }

    /**
     * Get stylesheet added.
     * 
     * This method return all
     * of added stylesheet
     * into an array.
     * 
     * @return array
     */
    public function getStylesheetAdded()
    {
        return $this->added;
    }

    /**
     * Set stylesheet added.
     * 
     * This method allow to
     * set the array of 
     * added stylesheet.
     * 
     * @param array $added The added array
     * 
     * @return StyleProcessor
     */
    public function setStylesheetAdded(array $added)
    {
        $this->added = $added;
        return $this;
    }

    /**
     * Process.
     * 
     * This method process the
     * SubTagedInterface and
     * insert the requested
     * stylesheets path into a
     * TwigStylesheet tag.
     * 
     * Will remove duplicate
     * stylesheets.
     * 
     * @param SubTagedInterface $tagContainer The tagContainer whence extract the needed stylesheets path.
     * @param boolean           $cssRewrite   The cssRewrite state of the TwigStylesheet (true as default)
     * 
     * @return TwigStylesheet
     */
    public function process(SubTagedInterface $tagContainer, $cssRewrite = true)
    {
        $stylesheet = new TwigStylesheet();
        $stylesheet->setCssRewrite($cssRewrite);
        
        $styles = $this->search($tagContainer);
        $styles = array_unique(array_merge($this->added, $styles));
        
        $this->assign($styles, $stylesheet);
        return $stylesheet;
    }

    /**
     * Search.
     * 
     * This method search all
     * stylesheets path requested
     * into a SubTagedInterface.
     * 
     * @param SubTagedInterface $tagContainer The tag container whence extract the stylesheets path.
     * 
     * @return array
     */
    protected function search(SubTagedInterface $tagContainer)
    {
        $tags = $tagContainer->getSubTags();
        $tags[] = $tagContainer;
        $styles = array();
        
        foreach ($tags as $tag) {
            if ($tag instanceof StylizedInterface) {
                if ($tag->hasStyle()) {
                    foreach ($tag->getStyles() as $style) {
                        $styles[] = $style;
                    }
                }
            }
        }
        
        return array_diff(array_unique($styles), $this->skip);
    }

    /**
     * Assign.
     * 
     * This method assign a set of stylesheet
     * path into a TwigStylesheet tag.
     * 
     * @param array          $styles      The stylesheet path array
     * @param TwigStylesheet &$stylesheet The TwigStylesheet where inject paths
     * 
     * @return void
     */
    protected function assign(array $styles, TwigStylesheet &$stylesheet)
    {
        foreach ($styles as $style) {
            $stylesheet->addStylesheet($style);
        }
    }
}
