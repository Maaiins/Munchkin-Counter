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

namespace App\Controller;

use App\Controller\Traits\OfflineCheckerTrait;
use App\Entity\App;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BaseController
 *
 * @category   Controller
 * @package    App\Controller
 * @author     Lauser, Nicolai <munchkin-counter@lauser.info>
 * @copyright  2019 Lauser, Nicolai
 * @version    $Id$
 */
class BaseController extends AbstractController
{
    use OfflineCheckerTrait;

    /**
     * @Route("/", name="index")
     * @Template("index.html.twig")
     *
     * @param EntityManagerInterface $em
     * @return array|Response
     */
    public function index(EntityManagerInterface $em)
    {
        if ($this->checkOffline($em)) {
            return new Response('', 403);
        }

        return [];
    }

    /**
     * @Route("/manage", name="manage")
     * @Template("manage.html.twig")
     *
     * @param EntityManagerInterface $em
     * @return array|Response
     */
    public function manage(EntityManagerInterface $em)
    {
        if ($this->checkOffline($em)) {
            return new Response('', 403);
        }

        return [];
    }

    /**
     * @Route("/offline", name="lock")
     * @Template("offline.html.twig")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param string $offlineKey
     * @return array
     */
    public function offline(Request $request, EntityManagerInterface $em, string $offlineKey)
    {
        if ($request->get('password') === $offlineKey) {
            $repository = $em->getRepository(App::class);

            $app = $repository->find(1);

            if (!$app) {
                $app = new App();
                $em->persist($app);
            }

            $app->setOffline(!$app->getOffline());

            $em->flush($app);
        }

        return [];
    }
}