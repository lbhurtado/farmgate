<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\GroupRepository;
use App\Entities\Group;

class GroupTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function group_has_name()
    {
        $group = $this->app->make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Group 1"
        ]);

        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals('Group 1', $group->name);
        $this->seeInDatabase($group->getTable(), [
            'name' => "Group 1"
        ]);
    }

    /** @test */
    function group_name_field_is_required()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(GroupRepository::class)->create([]);
    }

    /** @test */
    function group_name_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(GroupRepository::class)->create([
            'name'  => '',
        ]);
    }

    /** @test */
    function group_factory_is_valid(){
        $group = factory(Group::class)->create();
        $validator = Validator::make(
            $group->all()->toArray(),
            [
                'name' => array('required,min:2'),
            ]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    function group_has_a_presenter()
    {
        $contact = $this->app->make(GroupRepository::class)->create([
            'name' => 'Group 1'
        ]);

        $this->assertEquals(
            'Group 1',
            $contact['data']['name']
        );
    }

    /** @test */
    function group_has_unique_name_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $this->app->make(GroupRepository::class)->create([
            'name' => 'Group 1'
        ]);

        $this->app->make(GroupRepository::class)->create([
            'name' => 'Group 1'
        ]);
    }

    /** @test */
    function seed_groups()
    {
        $this->artisan('db:seed');

        $this->seeInDatabase('groups', ['name' => "Legal"]);
        $this->seeInDatabase('groups', ['name' => "Security"]);
    }
}
