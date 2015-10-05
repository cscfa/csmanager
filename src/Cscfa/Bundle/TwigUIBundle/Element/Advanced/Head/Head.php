<?php
/**
 * This file is a part of CSCFA TwigUi project.
 * 
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Tag
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\TwigUIBundle\Element\Advanced\Head;

use Cscfa\Bundle\TwigUIBundle\Element\Base\Tag;
use Cscfa\Bundle\ToolboxBundle\Set\TypedList;
use Cscfa\Bundle\TwigUIBundle\Element\Base\TextTag;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\HTMLTargetInterface;
use Cscfa\Bundle\TwigUIBundle\Element\Base\TagContainer;

/**
 * Head class.
 *
 * The Head class is used
 * to create an html head.
 *
 * @category Tag
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class Head extends Tag
{

    /**
     * Title.
     * 
     * This property indicate
     * the current page title.
     * 
     * @var string
     */
    protected $title;

    /**
     * Style.
     * 
     * This property indicate
     * the style tag content.
     * 
     * @var string
     */
    protected $style;

    /**
     * Base.
     * 
     * This property specifies 
     * the base URL/target for 
     * all relative URLs in a 
     * document.
     * 
     * @var Tag
     */
    protected $base;

    /**
     * Script.
     * 
     * This property define a 
     * client-side script, such 
     * as a JavaScript.
     * 
     * @var string
     */
    protected $script;

    /**
     * No script.
     * 
     * This property defines an 
     * alternate content for users 
     * that have disabled scripts 
     * in their browser or have a 
     * browser that doesn't support 
     * script.
     * 
     * @var string
     */
    protected $noScript;

    /**
     * Meta.
     * 
     * This property provides 
     * metadata about the HTML 
     * document.
     * 
     * @var TypedList
     */
    protected $meta;

    /**
     * Link.
     * 
     * This property defines a link 
     * between a document and an 
     * external resource.
     * 
     * @var TypedList
     */
    protected $link;

    /**
     * Default constructor.
     * 
     * This constructor initialize
     * the properties.
     * 
     * @param string $tagName The current tag name (head as default)
     * @param string $title   The current page title
     */
    public function __construct($tagName = "head", $title = "")
    {
        parent::__construct($tagName);
        
        $this->title = $title;
        $this->style = "";
        $this->script = "";
        $this->noScript = "";
        
        $this->base = null;
        
        $this->meta = new TypedList(new Tag());
        $this->link = new TypedList(new Tag());
    }

    /**
     * Get title.
     * 
     * This method return the
     * current title.
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
     * This method allow to set
     * the current title.
     * 
     * @param string $title The title as string
     * 
     * @return Head
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get style.
     * 
     * This method return the current
     * style content.
     * 
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set style.
     * 
     * This method allow to set
     * the current style content.
     * 
     * @param string $style The style content as string
     * 
     * @return Head
     */
    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }

    /**
     * Get base.
     * 
     * This method return the current
     * base Tag or NULL if not defined.
     * 
     * @return Tag|null
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set base.
     * 
     * This method allow to set
     * the current base tag.
     * 
     * @param string $href   The base href attribute
     * @param string $target The base target attribute
     * 
     * @return Head
     */
    public function setBase($href = null, $target = HTMLTargetInterface::BLANK_TARGET)
    {
        if ($href === null) {
            $this->base = null;
        } else {
            $tag = new Tag("base");
            $tag->getAttributes()->add("href", $href);
            $tag->getAttributes()->add("target", $target);
            $tag->setInline(true);
            
            $this->base = $tag;
        }
        
        return $this;
    }

    /**
     * Get script.
     *
     * This method return the
     * current script tag 
     * content.
     *
     * @return string
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Set script.
     *
     * This method allow to set 
     * the current script tag
     * content.
     *
     * @param string $script The script content as string
     * 
     * @return Head
     */
    public function setScript($script)
    {
        $this->script = $script;
        return $this;
    }

    /**
     * Get no script.
     * 
     * This method return the
     * current noScript tag
     * content.
     * 
     * @return string
     */
    public function getNoScript()
    {
        return $this->noScript;
    }

    /**
     * Set no script.
     * 
     * This method allow to set
     * the current noScript tag
     * content.
     * 
     * @param string $noScript The noScript tag content as string
     * 
     * @return Head
     */
    public function setNoScript($noScript)
    {
        $this->noScript = $noScript;
        return $this;
    }

    /**
     * Set charset.
     * 
     * This method allow to create
     * a meta tag with charset
     * information.
     * 
     * @param string $charset The meta charset value
     * 
     * @return Head
     */
    public function setCharset($charset)
    {
        $this->addMeta("", "", $charset, "", "");
        
        return $this;
    }

    /**
     * Add meta.
     * 
     * This method allow to
     * insert or replace a
     * new meta tag.
     * 
     * @param string $name      The name attribute value
     * @param string $content   The content attribute value
     * @param string $charset   The charset attribute value
     * @param string $httpEquiv The http_equiv attribute value
     * @param string $scheme    The scheme attribute value
     * 
     * @return void
     */
    public function addMeta($name = "", $content = "", $charset = "", $httpEquiv = "", $scheme = "")
    {
        $meta = new Tag("meta");
        $meta->setInline(true);
        
        if (! empty($name)) {
            $meta->getAttributes()->add("name", $name);
        }
        if (! empty($content)) {
            $meta->getAttributes()->add("content", $content);
        }
        if (! empty($charset)) {
            $meta->getAttributes()->add("charset", $charset);
        }
        if (! empty($httpEquiv)) {
            $meta->getAttributes()->add("http_equiv", $httpEquiv);
        }
        if (! empty($scheme)) {
            $meta->getAttributes()->add("scheme", $scheme);
        }
        
        if (! $meta->getAttributes()->isEmpty() && ! $this->meta->contain($meta)) {
            $this->meta->add($meta);
        } else if (! $meta->getAttributes()->isEmpty()) {
            $this->meta->remove($meta);
            $this->meta->add($meta);
        }
    }

    /**
     * Get meta.
     * 
     * This method return the
     * meta typed list.
     * 
     * @return TypedList
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set meta.
     * 
     * This method allow to
     * set the meta TypedList.
     * 
     * Note that the TypedList
     * must be typed for Tag.
     * 
     * @param TypedList $meta The meta TypedList
     * 
     * @return Head
     */
    public function setMeta(TypedList $meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Add link.
     * 
     * This method allow to insert
     * a new link Tag.
     * 
     * @param Tag $link The link tag to insert.
     * 
     * @return Head
     */
    public function addLink(Tag $link)
    {
        $this->link->add($link);
        
        return $this;
    }

    /**
     * Get link.
     * 
     * This method return the
     * link TypedList.
     * 
     * @return TypedList
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link.
     *
     * This method allow to
     * set the link TypedList.
     *
     * Note that the TypedList
     * must be typed for Tag.
     *
     * @param TypedList $link The link TypedList
     * 
     * @return Head
     */
    public function setLink(TypedList $link)
    {
        $this->link = $link;
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
        $start = str_repeat("\t", $this->getNestedLevel()) . "<";
        $tagName = strtolower($this->tagName);
        $start .= $tagName;
        
        $attrs = $this->computeAttributesToString();
        if (strlen($attrs) > 0) {
            $start .= " " . $attrs;
        }
        
        if ($this->isInline()) {
            return $start . "/>\n";
        } else {
            $start .= ">\n";
            $end = str_repeat("\t", $this->getNestedLevel()) . "</$tagName>\n";
        }
        
        $content = "";
        foreach ($this->computeHeadTags()
            ->getChilds()
            ->getAll() as $child) {
            if (is_object($child) && method_exists($child, "__toString")) {
                $content .= $child->__toString();
            }
        }
        foreach ($this->childs->getAll() as $child) {
            if (is_object($child) && method_exists($child, "__toString")) {
                $content .= $child->__toString();
            }
        }
        
        return $start . $content . $end;
    }

    /**
     * Compute head tags.
     * 
     * This method compute the head's
     * tags to string.
     * 
     * @return TagContainer
     */
    protected function computeHeadTags()
    {
        $container = new TagContainer();
        
        $strings = array(
            array(
                "title",
                "title"
            ),
            array(
                "style",
                "style"
            ),
            array(
                "script",
                "script"
            ),
            array(
                "noScript",
                "noscript"
            )
        );
        
        foreach ($strings as $definition) {
            if (! empty($this->{$definition[0]})) {
                $tmpTag = new Tag($definition[1]);
                $tmpTag->addChild(new TextTag($this->{$definition[0]}));
                $container->addChild($tmpTag);
            }
        }
        
        if ($this->base !== null) {
            $container->addChild($this->base);
        }
        
        foreach ($this->meta->getAll() as $meta) {
            $container->addChild($meta);
        }
        foreach ($this->link->getAll() as $link) {
            $container->addChild($link);
        }
        
        $container->setNestedLevel($this->getNestedLevel());
        return $container;
    }

    /**
     * Get sub tags.
     * 
     * Return all of the Tag
     * childs.
     * 
     * @return array
     */
    public function getSubTags()
    {
        $result = parent::getSubTags();
        
        $headContainer = $this->computeHeadTags();
        $result[] = $headContainer;
        foreach ($headContainer->getSubTags() as $subTag) {
            $result[] = $subTag;
        }
        
        return $result;
    }

    /**
     * Get sub tags count.
     *
     * Return the sub tags count.
     *
     * @return integer
     */
    public function getSubTagsCount()
    {
        $result = parent::getSubTagsCount();
        
        $result ++;
        $result += $this->computeHeadTags()->getSubTagsCount();
        
        return $result;
    }
}
