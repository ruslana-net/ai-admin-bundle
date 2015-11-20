<?php

namespace Ai\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use FOS\UserBundle\Model\GroupInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BeUser
 *
 * @ORM\Table(name="be_users")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class BeUser extends BaseUser
{
    const ROLE_ADMIN = 'ROLE_SONATA_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';

    public static $ADMIN_ROLES = array(
        self::ROLE_DEFAULT => 'Обычный',
        self::ROLE_SUPER_ADMIN => 'Супер Админ',
        self::ROLE_ADMIN => 'Админ',
        self::ROLE_MANAGER => 'Менеджер',
    );

    public static $UploadFolder='uploads/beusers';

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
     * @ORM\Column(name="tstamp", type="datetime")
     */
    private $tstamp;

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
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=255, nullable=true)
     */
    private $skype;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Ai\AdminBundle\Entity\BeGroup", inversedBy="users")
     * @ORM\JoinTable(name="be_users_groups")
     */
    protected  $groups;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tstamp
     *
     * @param \DateTime $tstamp
     * @return BeUser
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    /**
     * Get tstamp
     *
     * @return \DateTime 
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set crdate
     *
     * @param \DateTime $crdate
     * @return BeUser
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
     * @return BeUser
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
     * Set firstName
     *
     * @param string $firstName
     * @return BeUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return BeUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return BeUser
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set skype
     *
     * @param string $skype
     * @return BeUser
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Add group
     * @param GroupInterface $group
     * @param \Ai\AdminBundle\Entity\BeGroup $group
     * @return BeUser
     * @return $this
     */
    public function addGroup(GroupInterface $group)
    {
        return parent::addGroup($group);
    }

    /**
     * Remove group
     * @param GroupInterface $group
     * @param \Ai\AdminBundle\Entity\BeGroup $group
     * @return $this
     */
    public function removeGroup(GroupInterface $group)
    {
        return parent::removeGroup($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function __toString(){
        return strval($this->getUsername());
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return FeUser
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return bool
     */
    public function hasImg()
    {
        return (bool)$this->getAvatar();
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        // check if we have an old image avatar
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        }

        $this->setTstamp(new \Datetime('now'));
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return uniqid(rand(), true).'.'.$this->getFile()->guessExtension();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->avatar = $this->getFileName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getAvatar()
        );

        // remove previous file
        if (is_file($this->temp)) {
            unlink($this->temp);
        }

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (is_file($this->getAbsolutePath())) {
            unlink($this->getAbsolutePath());
        }
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->getAvatar()
            ? null
            : $this->getUploadRootDir().'/'.$this->getAvatar();
    }

    /**
     *
     */
    public function getWebPath()
    {
        return null === $this->getAvatar()
            ? null
            : $this->getUploadDir().'/'.$this->getAvatar();
    }

    /**
     *
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    /**
     *
     */
    protected function getUploadDir()
    {
        return self::$UploadFolder;
    }
}
