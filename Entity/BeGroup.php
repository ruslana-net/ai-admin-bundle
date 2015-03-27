<?php

namespace Ai\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\Group as BaseGroup;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BeGroup
 *
 * @ORM\Table(name="be_groups")
 * @ORM\Entity
 */
class BeGroup extends BaseGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="tmstamp", type="datetime")
     */
    private $tmstamp;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="crdate", type="datetime")
     */
    private $crdate;

    /**
     * @var integer
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Ai\AdminBundle\Entity\BeUser", mappedBy="groups")
     */
    protected $users;

    /**
     *
     */
    public function __construct(){
        $roles = array(
            BeUser::ROLE_SUPER_ADMIN,
            BeUser::ROLE_ADMIN,
            BeUser::ROLE_MANAGER,
            BeUser::ROLE_DEFAULT,
        );

        parent::__construct('', $roles );

        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tmstamp
     *
     * @param \DateTime $tmstamp
     * @return BeGroup
     */
    public function setTmstamp($tmstamp)
    {
        $this->tmstamp = $tmstamp;

        return $this;
    }

    /**
     * Get tmstamp
     *
     * @return \DateTime 
     */
    public function getTmstamp()
    {
        return $this->tmstamp;
    }

    /**
     * Set crdate
     *
     * @param \DateTime $crdate
     * @return BeGroup
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;

        return $this;
    }

    /**
     * Get crdate
     *
     * @return \DateTime 
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return BeGroup
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Add users
     *
     * @param \Ai\AdminBundle\Entity\BeUser $users
     * @return BeGroup
     */
    public function addUser(\Ai\AdminBundle\Entity\BeUser $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Ai\AdminBundle\Entity\BeUser $users
     */
    public function removeUser(\Ai\AdminBundle\Entity\BeUser $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return string
     */
    public function __toString(){
        return strval($this->getName());
    }
}
