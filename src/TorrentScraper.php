<?php
namespace Stien\Torrent;

class TorrentScraper {
	protected $adapters = [];

	public function addAdapter(TorrentAdapterInterface $adapter)
	{
		$this->adapters[] = $adapter;
	}

	public function search($query)
	{
		$results = [];
		foreach($this->adapters as $adapter)
		{
			$results[] = $adapter->search($query);
		}

		return $this->sortResults($results);
	}

	protected function sortResults($results)
	{
		// TODO: Do actual sorting.
		return $results;
	}
}