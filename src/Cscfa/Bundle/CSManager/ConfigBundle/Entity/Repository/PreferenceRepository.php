<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Repository
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\ConfigBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PreferenceRepository class.
 *
 * The PreferenceRepository class purpose feater to
 * quikly find a Preference or a set of Preference
 * from the database.
 *
 * @category Repository
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class PreferenceRepository extends EntityRepository
{
    /**
     * Get current or null.
     *
     * This method return the current
     * preference instance.
     *
     * @return mixed|null
     */
    public function getCurrentOrNull()
    {
        $preferences = $this->findBy(array('deleted' => false), array('created' => 'DESC'), 1);

        if (is_array($preferences) && count($preferences) == 1) {
            return $preferences[0];
        } else {
            return;
        }
    }
}
