<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ContactRepository;
use App\Entities\Group;

class ContactGroupsTest extends TestCase
{
    use DatabaseMigrations;

    private $contact;

    function setUp()
    {
        parent::setUp();

        $this->contact = App::make(ContactRepository::class)->skipPresenter()->create([
            'mobile' => '09173011987',
            'handle' => "lbhurtado"
        ]);
    }

    /** @test */
    function contact_has_groups()
    {
        $group1 = factory(Group::class)->create(['name' => "Group 1"]);
        $group2 = factory(Group::class)->create(['name' => "Group 2"]);
        $this->contact->groups()->attach($group1);
        $this->contact->groups()->attach($group2);

        $this->assertCount(2, $this->contact->groups);
        $this->assertEquals("Group 2", $this->contact->whereHandle("lbhurtado")->firstOrFail()->groups()->find($group2->id)->name);
        $this->assertEquals('lbhurtado', $group1->contacts()->find($this->contact->id)->handle);
        $this->seeInDatabase($this->contact->groups()->getTable(), ['contact_id' => $this->contact->id, 'group_id' => $group1->id]);
        $this->seeInDatabase($this->contact->groups()->getTable(), ['contact_id' => $this->contact->id, 'group_id' => $group2->id]);
    }
}
