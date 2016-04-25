<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\GroupRepository;
use App\Entities\Contact;

class GroupContactsTest extends TestCase
{
    use DatabaseMigrations;

    private $group;

    function setUp()
    {
        parent::setUp();

        $this->group = App::make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Group 1"
        ]);
    }

    /** @test */
    function group_has_contacts()
    {
        $contact1 = factory(Contact::class)->create(['handle' => "Lester"]);
        $contact2 = factory(Contact::class)->create(['handle' => "Dene"]);
        $this->group->members()->attach($contact1);
        $this->group->members()->attach($contact2);

        $this->assertCount(2, $this->group->members);

        $this->assertEquals("Dene", $this->group->whereName("Group 1")->firstOrFail()->members()->find($contact2->id)->handle);
        $this->assertEquals('Group 1', $contact1->groups()->find($this->group->id)->name);
    }
}
