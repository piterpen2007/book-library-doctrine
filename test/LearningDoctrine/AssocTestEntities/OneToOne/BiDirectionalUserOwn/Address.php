<?php

namespace EfTech\BookLibraryTest\LearningDoctrine\AssocTestEntities\OneToOne\BiDirectionalUserOwn;

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
     * @ORM\OneToOne(targetEntity=Address::class, mappedBy="address")
     *
     * @var User
     */
    private User $user;
}