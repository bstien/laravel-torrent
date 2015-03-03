<?php
namespace Stien\Torrent\Adapter;

use Stien\Torrent\BuildUrlTrait;
use Stien\Torrent\Categories as TC;
use Stien\Torrent\HttpClientTrait;
use Stien\Torrent\Result\Torrent;
use Stien\Torrent\TorrentAdapterInterface;
use Symfony\Component\DomCrawler\Crawler;

class KickassAdapter implements TorrentAdapterInterface {

	use HttpClientTrait;
	use BuildUrlTrait;

	private $tag = 'kickass';

	/**
	 * @param array $options
	 */
	public function __construct(array $options = null)
	{
		$this->setBaseUrl("https://kickass.to/");
		$this->setSearchUrl("usearch/%QUERY%+category:%CATEGORY%?field=seeders&sorder=desc&rss=1");
		$this->setCategoryIdentifiers([
			TC::ALL       => "all",
			TC::MOVIES    => "movies",
			TC::MOVIES_HD => "movies",
			TC::TV        => "tv",
			TC::TV_HD     => "tv",
			TC::ANIME     => "anime",
			TC::MUSIC     => "music",
			TC::BOOKS     => "books",
			TC::APPS      => "apps",
			TC::GAMES     => "games",
			TC::XXX       => "xxx",
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
			]
		];

		try
		{
			$url = $this->makeUrl($query, $category);
			$response = $this->httpClient->get($url, $requestOptions);
			$crawler = new Crawler((string)$response->getBody());
		} catch (\Exception $e)
		{
			return [];
		}

		$items = $crawler->filterXpath('//channel/item');

		$torrents = [];
		foreach ($items as $item)
		{
			$torrent = new Torrent();
			$itemCrawler = new Crawler($item);

			// Set details for torrent.
			$torrent->setSite($this->tag);
			$torrent->setTitle($itemCrawler->filterXpath('//title')->text());
			$torrent->setSeeders((int)$itemCrawler->filterXpath('//torrent:seeds')->text());
			$torrent->setLeechers((int)$itemCrawler->filterXpath('//torrent:peers')->text());
			$torrent->setMagnet($itemCrawler->filterXpath('//torrent:magnetURI')->text());
			$torrent->setSize($this->formatBytes((int)$itemCrawler->filterXPath('//torrent:contentLength')->text()));
			$torrent->setAge($itemCrawler->filterXPath('//pubDate')->text());
			$torrent->setCategory($itemCrawler->filterXPath('//category')->text());

			$torrents[] = $torrent;
		}

		return $torrents;
	}

	protected function formatBytes($bytes, $precision = 2)
	{
		// TODO: Move this method to either a helper or a trait
		$units = ['B', 'KB', 'MB', 'GB', 'TB'];

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		// Uncomment one of the following alternatives
		// $bytes /= pow(1024, $pow);
		$bytes /= (1 << (10 * $pow));

		return round($bytes, $precision) . ' ' . $units[$pow];
	}
}