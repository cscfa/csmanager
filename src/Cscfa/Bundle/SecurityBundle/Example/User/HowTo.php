<?php
/**
 * This file is a part of CSCFA security project.
 *
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Example
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\User
 * @see      Cscfa\Bundle\SecurityBundle\Entity\StackUpdate
 * @see      Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder
 * @see      Cscfa\Bundle\SecurityBundle\Util\Manager\UserManager
 * @see      Cscfa\Bundle\SecurityBundle\Util\Provider\UserProvider
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Base\StackableObject
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Repository\UserRepository
 */

namespace Cscfa\Bundle\SecurityBundle\Example\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cscfa\Bundle\SecurityBundle\Example\ExampleInterface;
use Cscfa\Bundle\SecurityBundle\Util\Manager\UserManager;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\SecurityBundle\Util\Provider\UserProvider;

/**
 * The HowToCreate class.
 *
 * This class present the User instance
 * creation.
 *
 * @category Example
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class HowTo extends Controller implements ExampleInterface
{
    /**
     * The howItWork method.
     *
     * This method present the creation
     * of a User instance.
     *
     * @see    \Cscfa\Bundle\SecurityBundle\Example\ExampleInterface::howItWork()
     */
    public function howItWork()
    {
        $manager = $this->getManager();
        $provider = $this->getProvider();

        // Create a new User :
        $userInstance = $manager->getNewInstance();

        $usernameResult = $userInstance->setUsername('UserName');
        if ($usernameResult) {
            // The username has been set
        } else {
            // the username setting faild. This can be caused by two issues.

            if ($userInstance->getLastError() == $userInstance::INVALID_USERNAME) {
                // the username is an invalid string
            } elseif ($userInstance->getLastError() == $userInstance::DUPLICATE_USERNAME) {
                // the username already exist into the database.
            }
        }
        /*
         * All of the setters can result false in devers case, but in all of them
         * the builder will conserve an error status.
         *
         * After setting all of the parameters, the instance can be persisted bellow the manager
         */

        $manager->persist($userInstance);

        // if this user doesn't used, it possible to remove it
        $manager->remove($userInstance);

        // it's possible to create a user instance and get the builder after it
        $user = new User();
        $convertedUser = $manager->convertInstance($user);

        // it's possible to get the user instance from the builder
        $convertedUser->getUser();
        // and the same for the stack update object
        $convertedUser->getStackUpdate();

        // It's possible to directly get a user builder from the provider
        $builder = $provider->findOneByEmail('email@test.ts');
        unset($builder);
        // and to get all usernames and emails
        $emails = $provider->findAllEmail();
        $usernames = $provider->findAllUsernames();
        unset($emails);
        unset($usernames);

        // note the the manager can test severals things
        $manager->isEmailValid('invalid email') === false;
        $manager->isUsernameValid('invalid name') === false;
    }

    /**
     * Get the manager.
     *
     * This method allow
     * to retreive the
     * UserManager service.
     *
     * @return UserManager
     */
    public function getManager()
    {
        return $this->get('core.manager.user_manager');
    }

    /**
     * Get the provider.
     *
     * This method allow
     * to retreive the
     * UserProvider service.
     *
     * @return UserProvider
     */
    public function getProvider()
    {
        return $this->get('core.provider.user_provider');
    }
}
