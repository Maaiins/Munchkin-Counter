<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"GET_PLAYERS"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"GET_PLAYERS"})
     */
    private $gender;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Serializer\Groups({"GET_PLAYERS"})
     */
    private $level;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Serializer\Groups({"GET_PLAYERS"})
     */
    private $equipment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Serializer\Groups({"GET_PLAYERS"})
     */
    private $bonus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getEquipment(): ?int
    {
        return $this->equipment;
    }

    public function setEquipment(?int $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getBonus(): ?int
    {
        return $this->bonus;
    }

    public function setBonus(?int $bonus): self
    {
        $this->bonus = $bonus;

        return $this;
    }
}
