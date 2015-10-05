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
 * @category Facade
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\MailBundle\Mailer\SwiftMailer;

use Cscfa\Bundle\MailBundle\BaseInterface\MailerInterface;
use Cscfa\Bundle\MailBundle\Entity\Message;
use Cscfa\Bundle\MailBundle\Entity\BodyPart;
use Cscfa\Bundle\MailBundle\Entity\Originator;
use Cscfa\Bundle\MailBundle\Formater\BNFFormater;
use Cscfa\Bundle\MailBundle\Formater\Util\BNFElement;
use Cscfa\Bundle\MailBundle\Entity\Receiver;
use Cscfa\Bundle\MailBundle\Entity\Specificator;
use Cscfa\Bundle\MailBundle\Entity\Syntactic;
use Cscfa\Bundle\MailBundle\Entity\Attachment;

/**
 * SwiftMailerFacade class.
 *
 * The SwiftMailerFacade class 
 * provide abstraction for
 * Swift_Mailer.
 *
 * @category Facade
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class SwiftMailerFacade implements MailerInterface
{

    /**
     * The mailer.
     * 
     * This property contain the
     * mailer service.
     * 
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * The message.
     * 
     * This property contain the
     * current message informations
     * to be used to send the message.
     * 
     * @var Message
     */
    protected $message;

    /**
     * Default constructor.
     * 
     * This constructor initialize
     * the properties and register
     * the mailer service.
     * 
     * @param \Swift_Mailer $mailer The mailer service to use
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->message = new Message();
    }

    /**
     * Mail.
     *
     * This method proceed to
     * the mailing.
     *
     * @see    \Cscfa\Bundle\MailBundle\BaseInterface\MailerInterface::mail()
     * @return void
     */
    public function mail()
    {
        $this->mailer->send($this->parse());
    }

    /**
     * Get message.
     * 
     * This method return the
     * current message instance.
     * 
     * @see    \Cscfa\Bundle\MailBundle\BaseInterface\MailerInterface::getMessage()
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message.
     * 
     * This method allow to set the
     * current message instance.
     * 
     * @param Message $message The new message instance
     * 
     * @see    \Cscfa\Bundle\MailBundle\BaseInterface\MailerInterface::setMessage()
     * @return void
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Parse.
     * 
     * This method parse the
     * current message into
     * Swift_Message instance
     * to be used by the mailer
     * to sent the mail.
     * 
     * @return Swift_Message
     */
    protected function parse()
    {
        $msg = $this->message;
        
        $swiftMessage = \Swift_Message::newInstance();
        
        $this->parseOriginator(
            $swiftMessage, 
            $msg->getHeader()
                ->getOriginator()
        );
        $this->parseReceiver(
            $swiftMessage, 
            $msg->getHeader()
                ->getReceiver()
        );
        $this->parseSpecificator(
            $swiftMessage, 
            $msg->getHeader()
                ->getSpecificator()
        );
        $this->parseSyntactic(
            $swiftMessage, 
            $msg->getHeader()
                ->getSyntactic()
        );
        
        if ($msg->getHeader()->getEncoder()->getEncoding() !== null) {
            $swiftMessage->setEncoder(
                $msg->getHeader()
                    ->getEncoder()
                    ->getEncoding()
            );
        }
        
        $swiftMessage->setBoundary($msg->getBoundary());
        $swiftMessage->setSubject($msg->getSubject());
        
        $bodies = $msg->getBodyParts()->getAll();
        foreach ($bodies as $bodyPart) {
            if ($bodyPart instanceof BodyPart) {
                $swiftMessage->addPart(
                    $bodyPart->getContent(), 
                    $bodyPart->getSyntactic()
                        ->getContentType()
                );
            }
        }
        
        $attachments = $msg->getAttachments()->getAll();
        foreach ($attachments as $attachment) {
            if ($attachment instanceof Attachment) {
                
                $att = \Swift_Attachment::newInstance();
                $att->setDisposition(
                    $attachment->getSyntactic()
                        ->getContentDisposition()
                );
                $att->setContentType(
                    $attachment->getSyntactic()
                        ->getContentType()
                );
                $att->setBody($attachment->getContent());
                $att->setFilename($attachment->getFileName());
                
                $swiftMessage->attach($att);
            }
        }
        
        return $swiftMessage;
    }

    /**
     * Parse syntactic.
     * 
     * This method parse the current
     * message header syntactic element
     * and hydrate the current 
     * Swift_mailer instance.
     * 
     * @param \Swift_Message &$swiftMessage The current Swift_Message instance reference
     * @param Syntactic      $syntactic     The Syntactic instance to parse
     * 
     * @return void
     */
    protected function parseSyntactic(\Swift_Message &$swiftMessage, Syntactic $syntactic)
    {
        $contentType = $syntactic->getContentType();
        $contentDescription = $syntactic->getContentDescription();
        
        if ($contentType !== null) {
            $swiftMessage->setContentType($contentType);
        }
        if ($contentDescription !== null) {
            $swiftMessage->setDescription($contentDescription);
        }
    }

    /**
     * Parse specificator.
     * 
     * This method parse the current
     * message header specificator 
     * element and hydrate the current 
     * Swift_mailer instance.
     * 
     * @param \Swift_Message &$swiftMessage The current Swift_Message instance reference
     * @param Specificator   $specificator  The Specificator instance to parse
     * 
     * @return void
     */
    protected function parseSpecificator(\Swift_Message &$swiftMessage, Specificator $specificator)
    {
        $date = $specificator->getDate();
        $id = $specificator->getMessageId();
        
        if ($date !== null && $date instanceof \DateTime) {
            $swiftMessage->setDate($date->getTimestamp());
        }
        if ($id !== null) {
            $swiftMessage->setId($id);
        }
    }

    /**
     * Parse receiver.
     * 
     * This method parse the current
     * message header receiver 
     * element and hydrate the current 
     * Swift_mailer instance.
     * 
     * @param \Swift_Message &$swiftMessage The current Swift_Message instance reference
     * @param Receiver       $receiver      The Receiver instance to parse
     * 
     * @return void
     */
    protected function parseReceiver(\Swift_Message &$swiftMessage, Receiver $receiver)
    {
        $to = $receiver->getTo();
        $cc = $receiver->getCc();
        $bcc = $receiver->getBcc();
        $bnf = new BNFFormater();
        
        if ($to !== null) {
            $this->hydrateMultipleValueName($swiftMessage, $bnf->parse($to), "addTo");
        }
        if ($cc !== null) {
            $this->hydrateMultipleValueName($swiftMessage, $bnf->parse($cc), "addCc");
        }
        if ($bcc !== null) {
            $this->hydrateMultipleValueName($swiftMessage, $bnf->parse($bcc), "addCc");
        }
    }

    /**
     * Parse originator.
     * 
     * This method parse the current
     * message header originator 
     * element and hydrate the current 
     * Swift_mailer instance.
     * 
     * @param \Swift_Message &$swiftMessage The current Swift_Message instance reference
     * @param Originator     $originator    The originator instance to parse
     * 
     * @return void
     */
    protected function parseOriginator(\Swift_Message &$swiftMessage, Originator $originator)
    {
        $from = $originator->getFrom();
        $replyTo = $originator->getReplyTo();
        $sender = $originator->getSender();
        $bnf = new BNFFormater();
        
        if ($from !== null) {
            $this->hydrateMultipleValueName($swiftMessage, $bnf->parse($from), "addFrom");
        }
        if ($replyTo !== null) {
            $this->hydrateMultipleValueName($swiftMessage, $bnf->parse($replyTo), "addReplyTo");
        }
        if ($sender !== null) {
            $this->hydrateMultipleValueName($swiftMessage, $bnf->parse($sender), "setSender");
        }
    }

    /**
     * Hydrate multiple value and name.
     * 
     * This method hydrate a Swift_Message
     * instance with multiple values and
     * names if exist.
     * 
     * @param \Swift_Message &$swiftMessage The current Swift_Message instance reference
     * @param BNFFormater    $formater      The provider of values and names
     * @param unknown        $method        The method to use for hydrate the Swift_Message
     * 
     * @return void
     */
    protected function hydrateMultipleValueName(\Swift_Message &$swiftMessage, BNFFormater $formater, $method)
    {
        foreach ($formater->getAllElements() as $element) {
            if ($element instanceof BNFElement) {
                if (! $element->hasSignificant()) {
                    continue;
                }
                
                if ($element->hasLabel()) {
                    $swiftMessage->$method($element->getSignificant(), $element->getLabel());
                } else {
                    $swiftMessage->$method($element->getSignificant());
                }
            }
        }
    }
}
