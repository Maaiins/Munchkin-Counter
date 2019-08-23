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
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiController
 *
 * @category   Controller
 * @package    App\Controller
 * @author     Lauser, Nicolai <munchkin-counter@lauser.info>
 * @copyright  2019 Lauser, Nicolai
 * @version    $Id$
 */
class ApiController extends AbstractController
{
    use OfflineCheckerTrait;

    /**
     * @Route("/api/game/new", name="api.game.new", methods={"GET"})
     *
     * @param EntityManagerInterface $em
     * @return JsonResponse|Response
     */
    public function newGame(EntityManagerInterface $em)
    {
        if ($this->checkOffline($em)) {
            return new Response('', 403);
        }

        $players = $this
            ->getDoctrine()
            ->getRepository(Player::class)
            ->findAll();

        foreach ($players as $player) {
            $em->remove($player);
        }

        $em->flush();

        return new JsonResponse(['success']);
    }

    /**
     * @Route("/api/player", name="api.player.new", methods={"POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse|Response
     */
    public function playerNew(Request $request, EntityManagerInterface $em)
    {
        if ($this->checkOffline($em)) {
            return new Response('', 403);
        }

        $player = $this
            ->getDoctrine()
            ->getRepository(Player::class)
            ->findOneByUsername($request->get('username'));

        if ($player) {
            return new JsonResponse(['success']);
        }

        $player = new Player();

        $player
            ->setUsername($request->get('username'))
            ->setGender('male')
            ->setLevel(1)
            ->setEquipment(0)
            ->setBonus(0);

        $em->persist($player);
        $em->flush();

        return new JsonResponse(['success']);
    }

    /**
     * @Route("/api/player/update", name="api.player.update", methods={"POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @return JsonResponse|Response
     */
    public function playerUpdate(Request $request, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        if ($this->checkOffline($em)) {
            return new Response('', 403);
        }

        $repository = $this
            ->getDoctrine()
            ->getRepository(Player::class);

        $player = $repository->findOneByUsername($request->get('username'));

        $player
            ->setGender($request->get('gender'))
            ->setLevel($request->get('level'))
            ->setEquipment($request->get('equipment'))
            ->setBonus($request->get('bonus'));

        $em->flush($player);

        return new JsonResponse($serializer->serialize($repository->findAll(), 'json', ['groups' => 'GET_PLAYERS']), 200, [], true);
    }

    /**
     * @Route("/api/players", name="api.players", methods={"GET"})
     *
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @return JsonResponse|Response
     */
    public function players(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        if ($this->checkOffline($em)) {
            return new Response('', 403);
        }

        $players = $this
            ->getDoctrine()
            ->getRepository(Player::class)
            ->findAll();

        return new JsonResponse($serializer->serialize($players, 'json', ['groups' => 'GET_PLAYERS']), 200, [], true);
    }
}