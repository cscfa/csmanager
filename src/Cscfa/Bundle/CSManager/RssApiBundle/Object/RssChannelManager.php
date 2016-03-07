<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Object
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\RssApiBundle\Object;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssUser;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\Channel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;

/**
 * RssChannelManager class.
 *
 * The RssUserManager implement
 * access method to rss channel.
 *
 * @category Object
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class RssChannelManager {

    /**
     * Manager
     * 
     * This attribute store
     * the application entity
     * manager
     * 
     * @var Registry
     */
    protected $doctrine;
    
    /**
     * RssChannelManager attribute
     * 
     * This attribute allow to
     * process a translation.
     * 
     * @var TranslatorInterface
     */
    protected $translator;
    
    /**
     * RssChannelManager attribute
     * 
     * This attribute allow to
     * create form.
     * 
     * @var FormFactoryInterface
     */
    protected $formFactory;
    
    /**
     * Set arguments
     * 
     * This method allow
     * to init the RssChannelManager
     * attributes
     * 
     * @param Registry             $doctrine    The doctrine entity manager
     * @param Translator           $translator  The translator service
     * @param FormFactoryInterface $formFactory The form factory service
     */
    public function setArguments(Registry $doctrine, TranslatorInterface $translator, FormFactoryInterface $formFactory){
        $this->doctrine = $doctrine;
        $this->translator = $translator;
        $this->formFactory = $formFactory;
    }
    
    /**
     * Get channel form
     * 
     * This method return
     * a new channel form
     * 
     * @param RssUser $user The RssUser that create a channel
     * 
     * @return FormInterface
     */
    public function getChannelForm(RssUser $user = null, Channel $channel = null){
        if ($user) {
            if ($channel === null) {
                $channel = new Channel();
                $channel->setUser($user);
            }
            return $this->formFactory->create("rssChannel", $channel);
        } else {
            return null;
        }
    }
    
    /**
     * Create channel
     * 
     * This method create and
     * register a new channel.
     * 
     * @param Request $request
     * @param FormInterface $form
     */
    public function createChannel(Request $request, FormInterface &$form){
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $itemTypes = $form->get("item")->getData();
            
            if (empty($itemTypes)) {
                $errorMessage = $this->translator->trans("notEmpty", [], "CscfaCSManagerRssApiBundle_form_ChannelType");
                $form->get("item")
                    ->addError(new FormError($errorMessage));
                
                return false;
            } else {
                $channel = $form->getData();
                
                if ($channel instanceof Channel) {
                    $channel->setItemTypes($itemTypes);
                    
                    $rssUserRepo = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssUser");
                    $rssUser = $rssUserRepo->find($form->get("user")->getData());
                    
                    if ($rssUser) {
                        $channel->setUser($rssUser);
                        $this->doctrine->getManager()->persist($channel);
                        $this->doctrine->getManager()->flush();
                    }else{
                        return false;
                    }
                } else {
                    return false;
                }
                
                return true;
            }
        } else {
            return false;
        }
        
    }
    
    /**
     * Get channel by hash
     * 
     * This method return a
     * channel by hashId or
     * null.
     * 
     * @param string $token The hashId
     * 
     * @return Channel|null
     */
    public function getChannelByHash($token){
        $repo = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\RssApiBundle\Entity\Channel");
        return $repo->findOneByHashId($token);
    }
}
