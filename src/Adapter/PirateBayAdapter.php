<?php
namespace Stien\Torrent\Adapter;

use Stien\Torrent\HttpClientTrait;
use Stien\Torrent\TorrentAdapterInterface;

class PirateBayAdapter implements TorrentAdapterInterface{
	use HttpClientTrait;

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

	}
}