<?php

namespace EfTech\BookLibraryTest\LearningDoctrine\AssocTestEntities\ManyToOne\BidirectionalProtected;

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
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="address")
     *
     * @var User
     */
    private User $user;

    /**
     * @param User $user
     * @return Address
     */
    public function registerUser(User $user): Address
    {
        if (false === $user->getAddress()->contains($user)) {
            $user->getAddress()->add($this);
        }
        $this->user = $user;
        return $this;
    }






}