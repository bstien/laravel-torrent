<?php
namespace Stien\Torrent\Adapter;

use Stien\Torrent\HttpClientTrait;
use Stien\Torrent\TorrentAdapterInterface;
use Symfony\Component\DomCrawler\Crawler;

class PirateBayAdapter implements TorrentAdapterInterface {

	use HttpClientTrait;

	private $baseUrl = "https://thepiratebay.se/";
	private $searchUrl = "search/%QUERY%/0/7/0";

	/**
	 * @param array $options
	 */
	public function __construct(array $options = null)
	{

	}

	/**
	 * Search for torrents.
	 *
	 * @param string $query
	 * @return array Array of torrents. Either empty or filled.
	 */
	public function search($query)
	{
		$url = $this->makeUrl($query);
		$response = $this->httpClient->get($url);

		$crawler = new Crawler((string)$response->getBody());
		$items = $crawler->filter('#searchResult tr');

		$results = [];
		$first = true;
		foreach ($items as $item)
		{
			// Ignore the first row, the header
			if ( $first )
			{
				$first = false;
				continue;
			}
			$result = new Torrent();
			$itemCrawler = new Crawler($item);
			$result->setName(trim($itemCrawler->filter('.detName')->text()));
			$result->setSeeders((int)$itemCrawler->filter('td')->eq(2)->text());
			$result->setLeechers((int)$itemCrawler->filter('td')->eq(3)->text());
			$result->setMagnetUrl($itemCrawler->filterXpath('//tr/td/a')->attr('href'));
			$results[] = $result;
		}

		return $results;
	}

	private function makeUrl($query, $category = null, $sort_by = null)
	{
		// TODO: Make URL based on category and sort.

		// Make URL.
		$url = $this->baseUrl . $this->searchUrl;

		// Replace placeholder with actual query.
		$query = urlencode($query);
		$url = preg_replace("/%QUERY%/", $query, $url);

		return $url;
	}
}