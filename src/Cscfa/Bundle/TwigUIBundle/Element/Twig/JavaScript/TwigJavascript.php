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

namespace Cscfa\Bundle\TwigUIBundle\Element\Twig\JavaScript;

use Cscfa\Bundle\TwigUIBundle\Element\Twig\Base\TwigTag;
use Cscfa\Bundle\ToolboxBundle\Set\ListSet;
use Cscfa\Bundle\TwigUIBundle\Element\Base\Tag;
use Cscfa\Bundle\TwigUIBundle\Element\Base\TextTag;

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
class TwigJavascript extends TwigTag
{
    /**
     * The script links set.
     *
     * This property represent
     * a collection of script
     * links.
     *
     * @var ListSet
     */
    protected $scriptLinked;

    /**
     * The script text set.
     *
     * This property represent
     * a collection of text
     * script.
     *
     * @var ListSet
     */
    protected $scriptText;

    /**
     * Default constructor.
     *
     * This constructor initialize
     * the properties.
     */
    public function __construct()
    {
        $this->nestedLevel = 0;
        $this->scriptLinked = new ListSet();
        $this->scriptText = new ListSet();
    }

    /**
     * Get script text.
     *
     * This method return the
     * current text script
     * collection.
     *
     * @return ListSet
     */
    public function getScriptText()
    {
        return $this->scriptText;
    }

    /**
     * Set text script.
     *
     * This method allow to set
     * the current script
     * collection.
     *
     * @param ListSet $scriptText The new text script collection.
     *
     * @return TwigJavascript
     */
    public function setScriptText(ListSet $scriptText)
    {
        $this->scriptText = $scriptText;

        return $this;
    }

    /**
     * Add text script.
     *
     * This method allow to
     * insert a new text
     * script.
     *
     * @param string $scriptText The text script to insert.
     *
     * @return TwigJavascript
     */
    public function addScriptText($scriptText)
    {
        $this->scriptText->add($scriptText);

        return $this;
    }

    /**
     * Get scripts linked.
     *
     * This method return the
     * current linked script
     * collection.
     *
     * @return ListSet
     */
    public function getScriptLinked()
    {
        return $this->scriptLinked;
    }

    /**
     * Set linked script.
     *
     * This method allow to
     * set the current linked
     * script collection.
     *
     * @param ListSet $scriptLinked The new ListSet to use as linked script collection.
     *
     * @return TwigJavascript
     */
    public function setScriptLinked(ListSet $scriptLinked)
    {
        $this->scriptLinked = $scriptLinked;

        return $this;
    }

    /**
     * Add linked script.
     *
     * This method allow to
     * insert a new linked
     * script into the current
     * script collection.
     *
     * @param string $scriptLinked The script link to insert.
     *
     * @return TwigJavascript
     */
    public function addScriptLinked($scriptLinked)
    {
        $this->scriptLinked->add($scriptLinked);

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
        $nesting = str_repeat("\t", $this->nestedLevel);
        $innerTag = new Tag('script');
        $innerTag->getAttributes()->add('src', '{{ asset_url }}');
        $innerTag->setNestedLevel($this->nestedLevel + 1);

        $textTag = new Tag('script');
        $textTag->addChild(new TextTag($this->computeTexts($nesting)));
        $textTag->setNestedLevel($this->nestedLevel + 1);

        $links = $this->computeLinks($nesting);

        $start = "$nesting{% javascripts $links%}\n";
        $content = "$innerTag\n";
        $content .= "$textTag\n";
        $end = "$nesting{% endjavascripts %}";

        return $start.$content.$end;
    }

    /**
     * Compute links.
     *
     * This method return the
     * computed string resulting
     * of the current links
     * collection implode.
     *
     * @param int $nesting The current tag nesting.
     *
     * @return string
     */
    protected function computeLinks($nesting)
    {
        $result = '';
        foreach ($this->scriptLinked->getAll() as $key => $link) {
            if ($key > 0) {
                $result .= "\n$nesting\t";
            }
            $result .= "\"$link\"";
        }

        return $result.' ';
    }

    /**
     * Compute texts.
     *
     * This method return the
     * computed string resulting
     * of the current texts
     * collection implode.
     *
     * @param int $nesting The current tag nesting.
     *
     * @return string
     */
    protected function computeTexts($nesting)
    {
        $result = "\n";
        $result .= implode("\n", $this->scriptText->getAll());
        $result = str_replace("\n", "\n$nesting\t\t", $result);

        return $result;
    }
}
