<?php

namespace EfTech\BookLibraryTest\LearningDoctrine\AssocTestEntities\OneToOne\BiDirectionalAddressOwn;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="address")
 *
 */
class Address
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, inversedBy="user")
     *
     * @var User
     */
    private User $user;
}