<?php
namespace Stien\Torrent\Result;

class Torrent {

	/**
	 * @var
	 */
	protected $title;

	/**
	 * @var
	 */
	protected $category;

	/**
	 * @var
	 */
	protected $size;

	/**
	 * @var
	 */
	protected $seeders;

	/**
	 * @var
	 */
	protected $leechers;

	/**
	 * @var
	 */
	protected $magnet;

	/**
	 * @var
	 */
	protected $age;

	/**
	 * @var
	 */
	protected $site;


	/**
	 * @return string
	 */
	public function getSite()
	{
		return $this->site;
	}

	/**
	 * @param string $site
	 */
	public function setSite($site)
	{
		$this->site = $site;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * @param string $category
	 */
	public function setCategory($category)
	{
		$this->category = $category;
	}

	/**
	 * @return string
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * @param string $size
	 */
	public function setSize($size)
	{
		$this->size = $size;
	}

	/**
	 * @return int
	 */
	public function getSeeders()
	{
		return $this->seeders;
	}

	/**
	 * @param int $seeders
	 */
	public function setSeeders($seeders)
	{
		$this->seeders = $seeders;
	}

	/**
	 * @return int
	 */
	public function getLeechers()
	{
		return $this->leechers;
	}

	/**
	 * @param int $leechers
	 */
	public function setLeechers($leechers)
	{
		$this->leechers = $leechers;
	}

	/**
	 * @return string
	 */
	public function getMagnet()
	{
		return $this->magnet;
	}

	/**
	 * @param string $magnet
	 */
	public function setMagnet($magnet)
	{
		$this->magnet = $magnet;
	}

	/**
	 * @return string
	 */
	public function getAge()
	{
		return $this->age;
	}

	/**
	 * @param string $age
	 */
	public function setAge($age)
	{
		$this->age = $age;
	}
}