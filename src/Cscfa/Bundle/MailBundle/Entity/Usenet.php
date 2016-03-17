<?php
/**
 * This file is a part of CSCFA mail project.
 *
 * The mail project is a tool bundle written in php
 * with Symfony2 framework to abstract a mail service
 * usage. It prevent the mail service change.
 *
 * PHP version 5.5
 *
 * @category   MailPart
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\MailBundle\Entity;

/**
 * Usenet class.
 *
 * The Usenet class indicate
 * the mail headers element
 * that can be found on standard
 * format for the interchange of
 * network News messages among
 * USENET hosts.
 *
 * @category MailPart
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class Usenet
{
    /**
     * The Newsgroup to sent.
     *
     * The "Newsgroups" property specifies
     * the newsgroup or newsgroups in which
     * the message belongs. Multiple
     * newsgroups may be specified, separated
     * by a comma. Newsgroups specified must
     * all be the names of existing newsgroups,
     * as no new newsgroups will be created
     * by simply posting to them.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $newsgroups;

    /**
     * The message path to reach.
     *
     * This property shows the path the
     * message took to reach the current
     * system.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $path;

    /**
     * The Newsgroup to follow up.
     *
     * This line has the same format as
     * "Newsgroups". If present, follow-up
     * messages are to be posted to the
     * newsgroup or newsgroups listed here.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $followupTo;

    /**
     * The message expiration date.
     *
     * This property specifies a suggested
     * expiration date for the message.
     *
     * @var \DateTime
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $expires;

    /**
     * The control state of the message.
     *
     * If a message contains a "Control" line,
     * the message is a control message. Control
     * messages are used for communication among
     * USENET host machines, not to be read by
     * users.
     *
     * @var bool
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $control;

    /**
     * The distribution scope of the message.
     *
     * This line is used to alter the
     * distribution scope of the message. It
     * is a comma separated list similar to
     * the "Newsgroups" line. User
     * subscriptions are still controlled by
     * "Newsgroups", but the message is sent
     * to all systems subscribing to the
     * newsgroups on the "Distribution" line
     * in addition to the "Newsgroups" line.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $distribution;

    /**
     * The sender organisation description.
     *
     * The text of this line is a short
     * phrase describing the organization to
     * which the sender belongs, or to which
     * the machine belongs.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $organization;

    /**
     * The message summary.
     *
     * This line should contain a brief
     * summary of the message. It is usually
     * used as part of a follow-up to another
     * message. Again, it is very useful to
     * the reader in determining whether to
     * read the message.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $summary;

    /**
     * The moderator mail adress.
     *
     * This line is required for any message
     * posted to a moderated newsgroup. It
     * should be added by the moderator and
     * consist of his mail address. It is also
     * required with certain control messages.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $approved;

    /**
     * The message body lines count.
     *
     * This contains a count of the number of
     * lines in the body of the message.
     *
     * @var int
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $lines;

    /**
     * The message number in a specifical newsgroup.
     *
     * This line contains the name of the
     * host (with domains omitted) and a
     * white space separated list of
     * colon-separated pairs of newsgroup
     * names and message numbers. These are
     * the newsgroups listed in the
     * "Newsgroups" line and the
     * corresponding message numbers from
     * the spool directory.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc1036
     */
    protected $xref;

    /**
     * Encoder default constructor.
     *
     * This constructor initialize the
     * contained property to null.
     */
    public function __construct()
    {
        $this->approved = null;
        $this->control = false;
        $this->distribution = null;
        $this->expires = null;
        $this->followupTo = null;
        $this->lines = 0;
        $this->newsgroups = null;
        $this->organization = null;
        $this->path = null;
        $this->summary = null;
        $this->xref = null;
    }

    /**
     * Get the Newsgroup to sent.
     *
     * This method return the newsgroup or
     * newsgroups in which the message belongs.
     *
     * @return string
     */
    public function getNewsgroups()
    {
        return $this->newsgroups;
    }

    /**
     * Set the Newsgroup to sent.
     *
     * This method allow to set the newsgroup or
     * newsgroups in which the message belongs.
     *
     * @param string $newsgroups the Newsgroup to sent
     *
     * @return Usenet
     */
    public function setNewsgroups($newsgroups)
    {
        $this->newsgroups = $newsgroups;

        return $this;
    }

    /**
     * Get the message path to reach.
     *
     * This method return the path of the
     * message took to reach the current
     * system.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the message path to reach.
     *
     * This method allow to set the path
     * of the message took to reach the
     * current system.
     *
     * @param string $path the message path to reach
     *
     * @return Usenet
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the follow-up newsgroup or newsgroups listed.
     *
     * This method return the follow-up
     * newsgroup or newsgroups listed of
     * the current message.
     *
     * @return string
     */
    public function getFollowupTo()
    {
        return $this->followupTo;
    }

    /**
     * Set the follow-up newsgroup or newsgroups listed.
     *
     * This method allow to set the follow-up
     * newsgroup or newsgroups listed of the
     * current message.
     *
     * @param string $followupTo the follow-up newsgroup or newsgroups listed
     *
     * @return Usenet
     */
    public function setFollowupTo($followupTo)
    {
        $this->followupTo = $followupTo;

        return $this;
    }

    /**
     * Get the message expiration date.
     *
     * This method return a suggested
     * expiration date for the message.
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set the message expiration date.
     *
     * This method allow to set a suggested
     * expiration date for the message.
     *
     * @param \DateTime $expires the message expiration date
     *
     * @return Usenet
     */
    public function setExpires(\DateTime $expires = null)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get the control state of the message.
     *
     * This method return true if the message
     * is a control message.
     *
     * @return bool
     */
    public function hasControl()
    {
        return $this->control;
    }

    /**
     * Set the control state of the message.
     *
     * This method allow set the message
     * control state.
     *
     * @param bool $control the control state of the message
     *
     * @return Usenet
     */
    public function setControl($control)
    {
        $this->control = $control;

        return $this;
    }

    /**
     * Get the distribution scope of the message.
     *
     * This method get the alteration of
     * the distribution scope of the message.
     *
     * @return string
     */
    public function getDistribution()
    {
        return $this->distribution;
    }

    /**
     * Set the distribution scope of the message.
     *
     * This method allow to set the alteration of
     * the distribution scope of the message.
     *
     * @param string $distribution the distribution scope of the message
     *
     * @return Usenet
     */
    public function setDistribution($distribution)
    {
        $this->distribution = $distribution;

        return $this;
    }

    /**
     * Get the sender organisation description.
     *
     * This method return a short phrase
     * describing the organization to which
     * the sender belongs, or to which the
     * machine belongs.
     *
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set the sender organisation description.
     *
     * This method allow to set a short phrase
     * describing the organization to which
     * the sender belongs, or to which the
     * machine belongs.
     *
     * @param string $organization the sender organisation description
     *
     * @return Usenet
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get the message summary.
     *
     * This method return a brief
     * summary of the message.
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set the message summary.
     *
     * This method allow to set a
     * brief summary of the message.
     *
     * @param string $summary the message summary
     *
     * @return Usenet
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get the moderator adress.
     *
     * This method return the
     * moderator adress.
     *
     * @return string
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set the moderator adress.
     *
     * This method allow to set
     * the moderator adress.
     *
     * @param string $approved the moderator adress
     *
     * @return Usenet
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get the message body lines count.
     *
     * This method return the count of the
     * lines number in the message body.
     *
     * @return number
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Set the message body lines count.
     *
     * This method allow to set the count
     * of the lines number in the message
     * body.
     *
     * @param int $lines the message body lines count
     *
     * @return Usenet
     */
    public function setLines($lines)
    {
        $this->lines = $lines;

        return $this;
    }

    /**
     * Get the message number in a specifical newsgroup.
     *
     * This method return the name of the
     * host (with domains omitted) and a
     * white space separated list of
     * colon-separated pairs of newsgroup
     * names and message numbers.
     *
     * @return string
     */
    public function getXref()
    {
        return $this->xref;
    }

    /**
     * Set the message number in a specifical newsgroup.
     *
     * This method allow to set the name
     * of the host (with domains omitted)
     * and a white space separated list of
     * colon-separated pairs of newsgroup
     * names and message numbers.
     *
     * @param string $xref the message number in a specifical newsgroup
     *
     * @return Usenet
     */
    public function setXref($xref)
    {
        $this->xref = $xref;

        return $this;
    }
}
