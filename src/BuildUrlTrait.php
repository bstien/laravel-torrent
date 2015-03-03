<?php
namespace Stien\Torrent;

use Stien\Torrent\Categories as TC;

trait BuildUrlTrait {

	/**
	 * @var string
	 */
	protected $baseUrl = "";
	/**
	 * @var string
	 */
	protected $searchUrl = "";
	/**
	 * @var
	 */
	protected $categoryIdentifiers;

	/**
	 * @param $baseUrl
	 */
	protected function setBaseUrl($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}

	/**
	 * Set placeholders where needed.
	 *
	 * %QUERY% - The query.
	 * %CATEGORY% - The category, as set via setCategoryIdentifiers
	 *
	 * @param $searchUrl
	 */
	protected function setSearchUrl($searchUrl)
	{
		$this->searchUrl = $searchUrl;
	}

	/**
	 * Set the keywords for categories, which are used to build the URL.
	 *
	 * @param array $identifiers
	 */
	protected function setCategoryIdentifiers(array $identifiers)
	{
		$this->categoryIdentifiers = $identifiers;
	}

	/**
	 * Build a full URL based on category and sorting order.
	 *
	 * @param      $query
	 * @param int  $category
	 * @param int $sort_by
	 * @return string
	 */
	protected function makeUrl($query, $category = TC::ALL, $sort_by = null)
	{
		// TODO: Make URL based on category and sort.
		// Make URL.
		$url = $this->baseUrl . $this->searchUrl;
		$url = $this->formatQuery($url, $query);
		$url = $this->formatCategory($url, $category);

		return $url;
	}

	/**
	 * Replace placeholder for query with query
	 *
	 * @param string $url
	 * @param string $query
	 * @return string
	 */
	protected function formatQuery($url, $query)
	{
		// Replace placeholder with actual query.
		$query = urlencode($query);

		return preg_replace("/%QUERY%/", $query, $url);
	}

	/**
	 * Replace placeholder for category with category
	 *
	 * @param string $url
	 * @param string $category
	 * @return string
	 */
	protected function formatCategory($url, $category)
	{
		if ( ! isset($this->categoryIdentifiers[$category]) )
		{
			return $url;
		}

		$category = $this->categoryIdentifiers[$category];

		return preg_replace("/%CATEGORY%/", $category, $url);
	}
}