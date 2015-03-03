<?php
namespace Stien\Torrent\Adapter;

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
		# Set single-cell view for torrents.
		$requestOptions = [
			'headers' => [
				'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
			],
			'cookies' => [
				'lw' => 's'
			]
		];


		try
		{
			$url = $this->makeUrl($query);
			$response = $this->httpClient->get($url, $requestOptions);
			$crawler = new Crawler((string)$response->getBody());
		} catch (\Exception $e)
		{
			// TODO: Log error. Some error has occured.
			return [];
		}

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


			$torrent = new Torrent();
			$itemCrawler = new Crawler($item);

			// Set details for torrent.
			$torrent->setSite("PirateBay");
			$torrent->setTitle(trim($itemCrawler->filter('td')->eq(1)->text()));
			$torrent->setSeeders((int)$itemCrawler->filter('td')->eq(5)->text());
			$torrent->setLeechers((int)$itemCrawler->filter('td')->eq(6)->text());
			$torrent->setMagnet($itemCrawler->filterXpath('/td[3]/a[0]')->attr('href'));
			$torrent->setSize($itemCrawler->filter('td')->eq(4)->text());
			$torrent->setAge($itemCrawler->filterXPath('/td[2]')->text());
			$torrent->setCategory($itemCrawler->filterXPath('/td[0]')->text());

			$torrents[] = $torrent;
		}

		return $torrents;
	}

	private function makeUrl($query, $category = null, $sort_by = null)
	{
		// TODO: Make URL based on category and sort by fields.
		// Make URL.
		$url = $this->baseUrl . $this->searchUrl;

		// Replace placeholder with actual query.
		$query = urlencode($query);
		$url = preg_replace("/%QUERY%/", $query, $url);

		return $url;
	}
}