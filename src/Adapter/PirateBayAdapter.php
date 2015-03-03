<?php
namespace Stien\Torrent\Adapter;

use Stien\Torrent\BuildUrlTrait;
use Stien\Torrent\Categories as TC;
use Stien\Torrent\HttpClientTrait;
use Stien\Torrent\Result\Torrent;
use Stien\Torrent\TorrentAdapterInterface;
use Symfony\Component\DomCrawler\Crawler;

class PirateBayAdapter implements TorrentAdapterInterface {

	use HttpClientTrait;
	use BuildUrlTrait;

	private $tag = 'piratebay';

	/**
	 * @param array $options
	 */
	public function __construct(array $options = null)
	{
		$this->setBaseUrl("https://thepiratebay.se/");
		$this->setSearchUrl("search/%QUERY%/0/7/%CATEGORY%");
		$this->setCategoryIdentifiers([
			TC::ALL       => "0",
			TC::MOVIES    => "201,207",
			TC::MOVIES_HD => "207",
			TC::TV        => "205,208",
			TC::TV_HD     => "208",
			TC::ANIME     => "602",
			TC::MUSIC     => "101,102,103,104",
			TC::BOOKS     => "601",
			// TODO: Should be able to sort by platform. Both apps and games.
			TC::APPS      => "301,302",
			TC::GAMES     => "401,402",
			// If anyone really needs this category, they can fix the sorting of porn
			// and send me a pull-request :)
			TC::XXX       => "501,502,503,504,505,506,599",
		]);
	}

	/**
	 * Search for torrents.
	 *
	 * @param string $query
	 * @param int    $category
	 * @return array Array of torrents. Either empty or filled.
	 */
	public function search($query, $category)
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
			$url = $this->makeUrl($query, $category);
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
			$torrent->setSite($this->tag);
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
}