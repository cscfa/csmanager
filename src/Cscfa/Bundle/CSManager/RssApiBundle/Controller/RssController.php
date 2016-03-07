<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Controller
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\RssApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssUser;
use Symfony\Component\HttpFoundation\Request;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\Channel;

/**
 * RssController class.
 *
 * The RssController implement
 * access method to rss api system.
 *
 * @category Controller
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class RssController extends Controller
{
    /**
     * Rss index action
     * 
     * This action render the
     * main project index page
     * 
     * @Template
     */
    public function indexAction()
    {
        $rssUser = $this->get("rss.userManager")->getRssUser($this->getUser());
        $channels = $this->get("rss.userManager")->getRssChannels($this->getUser());
        $channelForm = $this->get("rss.channelManager")->getChannelForm($rssUser);
        
        return array(
            "user"=>$rssUser,
            "channels"=>$channels,
            "channelForm"=>$channelForm!==null?$channelForm->createView():null
        );
    }
    
    /**
     * Rss create user action
     * 
     * This action create a
     * new RssUser for the current
     * User.
     * 
     * @return RedirectResponse
     */
    public function createAccountAction()
    {
        $this->get("rss.userManager")->createRssUser($this->getUser());
        return $this->redirect($this->generateUrl("cscfa_cs_manager_rss_api_homepage"));
    }
    
    /**
     * Rss create channel
     * 
     * This method allow to
     * create a new channel
     * 
     * @param Request $request The current request
     */
    public function createChannelAction(Request $request)
    {
        $rssUser = $this->get("rss.userManager")->getRssUser($this->getUser());
        $channels = $this->get("rss.userManager")->getRssChannels($this->getUser());
        $channelForm = $this->get("rss.channelManager")->getChannelForm($rssUser);
        
        if ($this->get("rss.channelManager")->createChannel($request, $channelForm)) {
            return $this->redirect($this->generateUrl("cscfa_cs_manager_rss_api_homepage"));
        } else {
            return $this->render("CscfaCSManagerRssApiBundle:Rss:index.html.twig", array(
                "user"=>$rssUser,
                "channels"=>$channels,
                "channelForm"=>$channelForm!==null?$channelForm->createView():null
            ));
        }
    }
    
    public function rssChannelAction($userToken, $channelToken){
        $channel = $this->get("rss.channelManager")->getChannelByHash($channelToken);
        $rssUser = $this->get("rss.userManager")->getRssUserByToken($userToken);
        
        if ($channel && $rssUser && $channel instanceof Channel && $rssUser instanceof RssUser) {
            if ($channel->getUser()->getId() === $rssUser->getId()) {
                
                $items = $this->get("rss.itemManager")->getItems($channel, $rssUser->getUser());
                
                return $this->render(
                    "CscfaCSManagerRssApiBundle:Default:index.xml.twig",
                    array(
                        "channel"=>$channel,
                        "items"=>$items,
                        "config"=>$this->getParameter("cscfa_rss_api_config")
                    )
                );
            } else {
                throw new \Exception("Forbidden authentification for this channel", 403);
            }
        } else {
            throw new \Exception("dismatch rss channel", 404);
        }
        exit();
    }
    
}
