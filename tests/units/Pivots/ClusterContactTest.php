<?php

use App\Entities\Contact;

class ClusterContactTest extends TestCase
{
    use DatabaseMigrationsWithSeeding;

    /** @test */
    function cluster_has_a_contact()
    {
        $cluster = factory(\App\Entities\Cluster::class)->create(['name' => 'Cluster 1']);
        $contact = factory(Contact::class)->create(['handle' => "Lester"]);
        $cluster->contacts()->associate($contact);
        $cluster->save();

        $this->assertCount(1, $cluster->contacts->all());
        $this->assertEquals('Lester', $cluster->contacts->handle);
        $this->seeInDatabase($cluster->getTable(), ['contact_id' => $contact->id]);
    }

    /** @test */
    function contact_has_a_cluster()
    {
        $cluster = factory(\App\Entities\Cluster::class)->create(['name' => 'Cluster 1']);
        $contact = factory(Contact::class)->create(['handle' => "Lester"]);
        $contact->cluster()->save($cluster);

        $this->assertCount(1, $contact->cluster->all());
        $this->assertEquals('Cluster 1', $contact->cluster->name);
        $this->seeInDatabase($cluster->getTable(), ['contact_id' => $contact->id]);
    }
}
