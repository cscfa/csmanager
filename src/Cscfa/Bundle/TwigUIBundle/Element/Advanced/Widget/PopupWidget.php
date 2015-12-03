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

use Cscfa\Bundle\TwigUIBundle\Element\Advanced\Widget\Widget;
use Cscfa\Bundle\TwigUIBundle\Element\Base\Tag;
use Cscfa\Bundle\TwigUIBundle\Element\Base\TextTag;
use Cscfa\Bundle\ToolboxBundle\Tool\Cache\CacheTool;

/**
 * PopupWidget class.
 *
 * The Widget class is used
 * to create an html popup
 * element.
 *
 * @category Widget
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class PopupWidget extends Widget
{

    /**
     * Widget title.
     * 
     * This property indicate
     * the widget title.
     * 
     * @var string
     */
    protected $title;

    /**
     * Widget closeable state.
     * 
     * This property indicate 
     * that the current widget
     * is closeable.
     * 
     * @var boolean
     */
    protected $closeable;

    /**
     * Widget display state.
     * 
     * This property indicate
     * the current widget
     * display state.
     * 
     * @var boolean
     */
    protected $displayed;

    /**
     * Default constructor.
     * 
     * This constructor initialize
     * the properties.
     * 
     * @param string  $title     The widget title.
     * @param boolean $closeable The widget closeable state. (true as default)
     * @param boolean $displayed The widget default display state. (false as default)
     */
    public function __construct(CacheTool $cacheTool, $title = "", $closeable = true, $displayed = false)
    {
        parent::__construct($cacheTool);
        
        $this->title = $title;
        $this->closeable = $closeable;
        $this->displayed = $displayed;
    }

    /**
     * Get closeable state.
     * 
     * This method return the
     * current widget closeable
     * state.
     * 
     * @return boolean
     */
    public function getCloseable()
    {
        return $this->closeable;
    }

    /**
     * Set closeable state.
     * 
     * This method allow to
     * set the current widget
     * closeable state.
     * 
     * @param boolean $closeable The current widget closeable state
     * 
     * @return PopupWidget
     */
    public function setCloseable($closeable)
    {
        $this->closeable = $closeable;
        return $this;
    }

    /**
     * Get displayed state.
     * 
     * This method return the
     * current widget displayed
     * state.
     * 
     * @return boolean
     */
    public function getDisplayed()
    {
        return $this->displayed;
    }

    /**
     * Set displayed state.
     * 
     * This method allow to
     * set the current widget
     * displayed state.
     * 
     * @param boolean $displayed The displayed state.
     * 
     * @return PopupWidget
     */
    public function setDisplayed($displayed)
    {
        $this->displayed = $displayed;
        return $this;
    }

    /**
     * Get title.
     * 
     * This method return the 
     * widget title.
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     * 
     * This method allow to
     * set the current widget
     * title.
     * 
     * @param string $title The widget title.
     * 
     * @return PopupWidget
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Compute tag.
     * 
     * This method return a string
     * that contain the current tag
     * HTML representation.
     * 
     * @return string
     */
    protected function computeTag()
    {
        $base = new Tag("section");
        $base->getClass()->add("popup_widget_base");
        $base->setNestedLevel($this->getNestedLevel());
        
        if (! $this->displayed) {
            $base->getClass()->add("popup_widget_base_hidden");
        }
        
        $titleContainer = new Tag("div");
        
        $titleContainer->getClass()->add("popup_widget_titleContainer");
        $title = new Tag("h6");
        $title->getClass()->add("popup_widget_title");
        $title->addChild(new TextTag($this->title));
        $titleContainer->addChild($title);
        
        if ($this->closeable) {
            $close = new Tag("button");
            $close->getClass()->add("popup_widget_close");
            
            $titleContainer->addChild($close);
        }
        
        $titleClear = new Tag("div");
        $titleClear->getClass()->add("popup_widget_title_clear");
        $titleContainer->addChild($titleClear);
        
        $content = new Tag("div");
        $content->getClass()->add("popup_widget_contentContainer");
        
        foreach ($this->childs->getAll() as $child) {
            if (is_object($child) && method_exists($child, "__toString")) {
                $content->addChild($child);
            }
        }
        
        $base->addChild($titleContainer)->addChild($content);
        
        return $base->__toString();
    }

    /**
     * Has style.
     * 
     * This method check if the
     * current element has style.
     * 
     * @return boolean
     */
    public function hasStyle()
    {
        return true;
    }

    /**
     * Get styles.
     * 
     * This method return all
     * of the element stylesheets.
     * 
     * @return array
     */
    public function getStyles()
    {
        return array(
            "@CscfaTwigUIBundle/Resources/public/css/widget/popupWidget.css"
        );
    }

    /**
     * Has script link.
     *
     * This method indicate the
     * script link existance
     * of the current instance.
     *
     * @return boolean
     */
    public function hasScriptLink()
    {
        return true;
    }

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
    public function getScriptLink()
    {
        return array(
            "@CscfaTwigUIBundle/Resources/public/js/widget/popupWidget.js"
        );
    }

    /**
     * Get script link count.
     *
     * This method return the
     * current instance script
     * links counts.
     *
     * @return integer
     */
    public function getScriptLinkCount()
    {
        return 0;
    }

    /**
     * Get custom script.
     *
     * This method indicate the
     * existance of customized
     * script.
     *
     * @return boolean
     */
    public function hasCustomScript()
    {
        return true;
    }

    /**
     * Get custom script.
     *
     * This method return the
     * current instance custom
     * script.
     *
     * @return string
     */
    public function getCustomScript()
    {
        $varName = $this->getCache("popup_widget_varname");
        if($varName === null){
            $varNum = 0;
            do{
                $varNum ++;
                $varName = "popupWidget".dechex($varNum);
            }while($this->getCacheFile()->hasValue($varName));
            
            $this->setCache("popup_widget_varname", $varName);
        }
        
        $script = "var $varName = new PopupWidget();\n$varName.process();\n";
        
        return array($script);
    }

    /**
     * Get custom script length.
     *
     * This method return the
     * current instance custom
     * script length.
     *
     * @return integer
     */
    public function getCustomScriptLength()
    {
        return strlen($this->getCustomScript());
    }
}
