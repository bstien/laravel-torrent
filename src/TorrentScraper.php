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
		$results = array_flatten($results);
		return $this->sortResults($results);
	}

	protected function sortResults($results)
	{
		// TODO: Do actual sorting.
		// For now, sort it by seeders.
		usort($results, function($a, $b){
			return $a->getSeeders() <= $b->getSeeders();
		});

		return $results;
	}
}