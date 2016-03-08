<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Twig extension
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Twig\Extension;

/**
 * MarkdownParserExtension class.
 *
 * The MarkdownParserExtension implement
 * method to parse string to markdown.
 *
 * @category Twig extension
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class MarkdownParserExtension extends \Twig_Extension {

    /**
     * Get functions
     * 
     * Return the function definitions
     * of the current twig extension.
     * 
     * @see Twig_Extension::getFunctions()
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('parseDown', array(
                $this,
                'parseDown'
            ), array(
                'is_safe' => array(
                    'html'
                )
            ))
        );
    }
    
    /**
     * Parse down
     * 
     * This method return
     * a markdown parsed
     * text.
     * 
     * @param string $baseText The string to parse
     * 
     * @return string
     */
    public function parseDown($baseText){

        $parser = new \Parsedown();
        return $parser->parse(htmlspecialchars($baseText));
        
    }
    
    /**
     * Get name
     * 
     * This method return the
     * extension name
     * 
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName() {
        return "markdown_parser.extension";
    }
}