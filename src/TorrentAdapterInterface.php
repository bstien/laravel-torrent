<?php
namespace Stien\Torrent;

use GuzzleHttp\Client;

interface TorrentAdapterInterface {

	/**
	 * @param array $options
	 */
	public function __construct(array $options = null);

	/**
	 * Search for torrents.
	 *
	 * @param string $query
	 * @return array Array of torrents. Either empty or filled.
	 */
	public function search($query);

	/**
	 * Set the HTTP-client for this adapter.
	 *
	 * @param Client $client
	 * @return void
	 */
	public function setHttpClient(Client $client);

	/**
	 * Return the adapters HTTP-client.
	 *
	 * @return Client
	 */
	public function getHttpClient();
}