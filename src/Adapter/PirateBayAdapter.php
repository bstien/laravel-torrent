<?php
namespace Stien\Torrent\Adapter;

use GuzzleHttp\Client;
use Stien\Torrent\HttpClientTrait;
use Stien\Torrent\Result\Torrent;
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
		# Set single-cell view for results.
		$requestOptions = [
			'cookies' => [
				'lw' => 's'
			]
		];
		$url = $this->makeUrl($query);
		$response = $this->httpClient->get($url, $requestOptions);

		$crawler = new Crawler((string)$response->getBody());
		$items = $crawler->filter('#searchResult tr');

		$torrents = [];
		$firstRow = true;
		foreach ($items as $item)
		{
			// Ignore the first row.
			if ( $firstRow )
			{
				$firstRow = false;
				continue;
			}


			$result = new Torrent();
			$itemCrawler = new Crawler($item);

			// Set details for torrent.
			$result->setSite("PirateBay");
			$result->setTitle(trim($itemCrawler->filter('td')->eq(1)->text()));
			$result->setSeeders((int)$itemCrawler->filter('td')->eq(5)->text());
			$result->setLeechers((int)$itemCrawler->filter('td')->eq(6)->text());
			$result->setMagnet($itemCrawler->filterXpath('/td[3]/a[0]')->attr('href'));
			$result->setSize($itemCrawler->filter('td')->eq(4)->text());
			$result->setAge($itemCrawler->filterXPath('/td[2]')->text());
			$result->setCategory($itemCrawler->filterXPath('/td[0]')->text());

			$torrents[] = $result;
		}

		return $torrents;
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