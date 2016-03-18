<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category TwigTag
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Element\Twig\StyleSheet;

use Cscfa\Bundle\ToolboxBundle\Set\ListSet;
use Cscfa\Bundle\TwigUIBundle\Element\Twig\Base\TwigTag;
use Cscfa\Bundle\TwigUIBundle\Element\Base\Tag;

/**
 * TwigStylesheet class.
 *
 * The TwigStylesheet class
 * is used to create a twig
 * element.
 *
 * @category TwigTag
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class TwigStylesheet extends TwigTag
{
    /**
     * Stylesheets.
     *
     * This property contain all
     * of the stylesheets path
     * to import.
     *
     * @var ListSet
     */
    protected $styleSheets;

    /**
     * @var bool
     */
    protected $cssRewrite;

    /**
     * Default constructor.
     *
     * This constructor initialize
     * the properties.
     */
    public function __construct()
    {
        $this->nestedLevel = 0;
        $this->styleSheets = new ListSet();
        $this->cssRewrite = false;
    }

    /**
     * Add stylesheet.
     *
     * This method allow to insert
     * a new stylesheet path.
     *
     * @param string $stylesheet The stylesheet path to assing
     *
     * @return TwigStylesheet
     */
    public function addStylesheet($stylesheet)
    {
        if (!$this->styleSheets->contain($stylesheet)) {
            $this->styleSheets->add($stylesheet);
        }

        return $this;
    }

    /**
     * Get stylesheets.
     *
     * This method return the
     * current ListSet of stylesheets.
     *
     * @return ListSet
     */
    public function getStyleSheets()
    {
        return $this->styleSheets;
    }

    /**
     * Set stylesheets.
     *
     * This method allow to set
     * the current ListSet instance
     * to register the stylesheets
     * to import.
     *
     * @param ListSet $styleSheets The stylesheet set
     *
     * @return TwigStylesheet
     */
    public function setStyleSheets(ListSet $styleSheets)
    {
        $this->styleSheets = $styleSheets;

        return $this;
    }

    /**
     * Get css rewrite.
     *
     * This method return the
     * current css rewrite rule
     * of the stylesheets block.
     *
     * @return bool
     */
    public function isCssRewrite()
    {
        return $this->cssRewrite;
    }

    /**
     * Set css rewrite.
     *
     * This method allow to set
     * the current css rewrite
     * rule.
     *
     * @param bool $cssRewrite The css rewrite rule state
     *
     * @return TwigStylesheet
     */
    public function setCssRewrite($cssRewrite)
    {
        $this->cssRewrite = $cssRewrite;

        return $this;
    }

    /**
     * To string.
     *
     * This method return the current
     * instance parsed as string.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->styleSheets->isEmpty()) {
            return '';
        }

        if ($this->cssRewrite) {
            $extra = 'filter="cssrewrite" ';
        } else {
            $extra = '';
        }

        $displayTag = new Tag('link');
        $displayTag->getAttributes()
            ->add('rel', 'stylesheet')
            ->add('href', '{{ asset_url }}');
        $displayTag->setInline(true);
        $displayTag->setNestedLevel($this->getNestedLevel() + 1);

        $tabs = str_repeat("\t", $this->nestedLevel);
        $glue = "\n\t".$tabs;
        $array = '';
        foreach ($this->styleSheets->getAll() as $stylesheet) {
            $array .= $glue."\"$stylesheet\"";
        }
        $array .= ' ';

        $start = "$tabs{% stylesheets ".$array.$extra."%}\n";
        $content = $displayTag->__toString();
        $end = "$tabs{% endstylesheets %}\n";

        return $start.$content.$end;
    }
}
