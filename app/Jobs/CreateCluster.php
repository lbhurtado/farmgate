<?php

namespace App\Jobs;

use App\Entities\Cluster;
use App\Repositories\PollingPlaceRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\BarangayRepository;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\ClusterRepository;
use Illuminate\Queue\SerializesModels;
use App\Repositories\TownRepository;

class CreateCluster extends Job
{
//    use InteractsWithQueue, SerializesModels;

    private $town;
    private $barangay;
    private $polling_place;
    private $precincts;
    private $cluster;
    private $registered_voters;

    /**
     * CreateCluster constructor.
     * @param $town
     * @param $barangay
     * @param $polling_place
     * @param $precincts
     * @param $cluster
     * @param $registered_voters
     */
    public function __construct($town, $barangay, $polling_place, $precincts, $cluster, $registered_voters)
    {
        $this->town = $town;
        $this->barangay = $barangay;
        $this->polling_place = $polling_place;
        $this->precincts = $precincts;
        $this->cluster = $cluster;
        $this->registered_voters = $registered_voters;
    }

    /**
     * @param PollingPlaceRepository $polling_places
     * @param BarangayRepository $barangays
     * @param ClusterRepository $clusters
     * @param TownRepository $towns
     */
    public function handle(ClusterRepository $clusters, PollingPlaceRepository $polling_places, BarangayRepository $barangays,
                           TownRepository $towns)
    {
        $town = $this->createTown($towns);

        $barangay = $this->createBarangay($barangays);
        $barangay->town()->associate($town)->save();

        $polling_place = $this->createPollingPlace($polling_places);
        $polling_place->barangay()->associate($barangay)->save();

        $cluster = $this->createCluster($clusters);
        $cluster->town()->associate($town)->polling_place()->associate($polling_place)->save();
    }

    /**
     * @param TownRepository $towns
     * @return mixed
     */
    protected function createTown(TownRepository $towns)
    {
        $name = trim($this->town);
        $town = $towns->skipPresenter()->updateOrCreate(compact('name'));

        return $town;
    }

    /**
     * @param BarangayRepository $barangays
     * @return mixed
     */
    protected function createBarangay(BarangayRepository $barangays)
    {
        $name = trim($this->barangay);
        $barangay = $barangays->skipPresenter()->updateOrCreate(compact('name'));

        return $barangay;
    }

    /**
     * @param PollingPlaceRepository $polling_places
     * @return mixed
     */
    protected function createPollingPlace(PollingPlaceRepository $polling_places)
    {
        $name = trim($this->polling_place);
        $polling_place = $polling_places->skipPresenter()->updateOrCreate(compact('name'));

        return $polling_place;
    }

    /**
     * @param ClusterRepository $clusters
     * @return mixed
     */
    protected function createCluster(ClusterRepository $clusters)
    {
        $name = trim($this->cluster);
        $precincts = trim($this->precincts);
        $registered_voters = (int) trim($this->registered_voters);
        $cluster = $clusters->skipPresenter()->updateOrCreate(compact('name', 'precincts', 'registered_voters'));

        return $cluster;
    }
}
