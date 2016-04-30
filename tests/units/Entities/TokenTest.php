<?php

use App\Repositories\ContactRepository;
use App\Repositories\TokenRepository;
use App\Repositories\GroupRepository;
use App\Entities\Group;

class TokenTest extends TestCase
{
    use DatabaseMigrationsWithSeeding;

    /** @test */
    function token_has_unique_code()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        App::make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'ABC1234',
            'class'      => Group::class,
            'reference'  => 1
        ]);

        App::make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'ABC1234',
            'class'      => Group::class,
            'reference'  => 2
        ]);
    }

    /** @test */
    function token_has_unique_object_and_object_id()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        App::make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'ABC1234',
            'class'      => Group::class,
            'reference'  => 1
        ]);

        App::make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'XYZ4321',
            'class'      => Group::class,
            'reference'  => 1
        ]);
    }

    /** @test */
    function tokens_can_be_claimed_by_a_contact_returning_the_object()
    {
        $group1 = App::make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Test Group 1"
        ]);

        $group2 = App::make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Test Group 2"
        ]);

        $tokens = App::make(TokenRepository::class)->skipPresenter();
        $claim_code = 'ABC1234';
        $unclaimed_code = 'XYZ4321';
        $tokens->create([
            'code'       => $claim_code,
            'class'      => Group::class,
            'reference'  => $group1->id
        ]);
        $tokens->create([
            'code'       => $unclaimed_code,
            'class'     => Group::class,
            'reference'  => $group2->id
        ]);

        $this->assertCount(2, $tokens->all());

        $contact = App::make(ContactRepository::class)->skipPresenter()->create([
            'mobile' => '09173011987',
            'handle' => "lbhurtado"
        ]);

        $object = $tokens->claim($contact, $claim_code);

        $this->assertInstanceOf(Group::class, $object);
        $this->assertEquals($group1->id, $object->id);
        $this->assertCount(1, $tokens->all());

        $token = $tokens->first();
        $this->assertEquals($unclaimed_code, $token->code);
    }

    /** @test */
    function tokens_can_be_generated()
    {
        $tokens = App::make(TokenRepository::class)->skipPresenter();
        $groups = App::make(GroupRepository::class)->skipPresenter();
        $tokens->generate($groups->all());

        $this->assertCount(count($groups->all()), $tokens->all());
    }

    /** @test */
    function tokens_can_auto_associate_contact()
    {
        $tokens = App::make(TokenRepository::class)->skipPresenter();
        $groups = App::make(GroupRepository::class)->skipPresenter();
        $contacts = App::make(ContactRepository::class)->skipPresenter();
        $tokens->generate($groups->all());

        $claim_code = 'ABC1234';

        $group1 = $groups->create([
            'name' => "Test Group 1"
        ]);

        $tokens->create([
            'code'       => $claim_code,
            'class'      => Group::class,
            'reference'  => $group1->id
        ]);

        $contact = $contacts->create([
            'mobile' => '09173011987',
            'handle' => "lbhurtado"
        ]);

        $token = $tokens->find(1);

        $group_object = $contact->claimToken($token->code);

//        $this->assertCount(1, $group_object->contacts);
//
//        $relation = $group_object->getTable();// get table name of relationship
//
//        $this->assertEquals(
//            $group_object->id,
//            $contact->$relation()->find($group_object->id)->id
//        );
//
//        $this->seeInDatabase($contact->groups()->getTable(), ['contact_id' => $contact->id, 'group_id' => $group_object->id]);
    }
}
