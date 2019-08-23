<?php
/**
 * This file is part of the Munchkin project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author     Lauser, Nicolai <munchkin-counter@lauser.info>
 * @copyright  2019 Lauser, Nicolai
 * @version    $Id$
 */

namespace App\Controller\Traits;

use App\Entity\App;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait OfflineCheckerTrait
 *
 * @category   Traits
 * @package    App\Controller\Traits
 * @author     Lauser, Nicolai <munchkin-counter@lauser.info>
 * @copyright  2019 Lauser, Nicolai
 * @version    $Id$
 */
trait OfflineCheckerTrait
{
    /**
     * @param EntityManagerInterface $em
     * @return bool
     */
    private function checkOffline(EntityManagerInterface $em)
    {
        $app = $em->getRepository(App::class)->find(1);

        return ($app && $app->getOffline());
    }
}