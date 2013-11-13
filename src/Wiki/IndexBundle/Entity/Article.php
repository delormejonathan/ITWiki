<?php

namespace Wiki\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wiki\IndexBundle\Entity\ArticleRepository")
 */
class Article
{   
	/**
		* @ORM\ManyToOne(targetEntity="Wiki\UserBundle\Entity\User")
		* @ORM\JoinColumn(nullable=false)
	*/
	private $creator;
	/**
		* @ORM\ManyToMany(targetEntity="Wiki\IndexBundle\Entity\Tag" , cascade={"persist"})
		* @ORM\JoinColumn(nullable=true)
	*/
	private $tags;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="title", type="string", length=255)
	 */
	private $title;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="content", type="text", nullable=true)
	 */
	private $content;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="`create`", type="datetime")
	 */
	private $create;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="`update`", type="datetime")
	 */
	private $update;

	/**
		* @Gedmo\Slug(fields={"title"})
		* @ORM\Column(length=128, unique=true)
	*/
	private $slug;

	public function __construct ()
	{
		$this->create = new \DateTime();
		$this->update = new \DateTime();
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
	 * Set title
	 *
	 * @param string $title
	 * @return Article
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	
		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string 
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 * @return Article
	 */
	public function setContent($content)
	{
		$this->content = $content;
	
		return $this;
	}

	/**
	 * Get content
	 *
	 * @return string 
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Set create
	 *
	 * @param \DateTime $create
	 * @return Article
	 */
	public function setCreate($create)
	{
		$this->create = $create;
	
		return $this;
	}

	/**
	 * Get create
	 *
	 * @return \DateTime 
	 */
	public function getCreate()
	{
		return $this->create;
	}

	/**
	 * Set update
	 *
	 * @param \DateTime $update
	 * @return Article
	 */
	public function setUpdate($update)
	{
		$this->update = $update;
	
		return $this;
	}

	/**
	 * Get update
	 *
	 * @return \DateTime 
	 */
	public function getUpdate()
	{
		return $this->update;
	}

	/**
	 * Set slug
	 *
	 * @param string $slug
	 * @return Article
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
	
		return $this;
	}

	/**
	 * Get slug
	 *
	 * @return string 
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Set creator
	 *
	 * @param \Wiki\UserBundle\Entity\User $creator
	 * @return Article
	 */
	public function setCreator(\Wiki\UserBundle\Entity\User $creator)
	{
		$this->creator = $creator;
	
		return $this;
	}

	/**
	 * Get creator
	 *
	 * @return \Wiki\UserBundle\Entity\User 
	 */
	public function getCreator()
	{
		return $this->creator;
	}

	/**
	 * Add tags
	 *
	 * @param \Wiki\IndexBundle\Entity\Tag $tags
	 * @return Article
	 */
	public function addTag(\Wiki\IndexBundle\Entity\Tag $tags)
	{
		$this->tags[] = $tags;
	
		return $this;
	}

	/**
	 * Remove tags
	 *
	 * @param \Wiki\IndexBundle\Entity\Tag $tags
	 */
	public function removeTag(\Wiki\IndexBundle\Entity\Tag $tags)
	{
		$this->tags->removeElement($tags);
	}

	/**
	 * Get tags
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getTags()
	{
		return $this->tags;
	}
	
	public function resetTags()
	{
		foreach ($this->getTags() as $tag) $this->removeTag($tag);
		return $this;
	}
}