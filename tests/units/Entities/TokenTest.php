<?php

use App\Repositories\TokenRepository;
use App\Repositories\GroupRepository;
use App\Repositories\ContactRepository;
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
        $tokens = App::make(TokenRepository::class)->skipPresenter();
        $tokens->create([
            'code'       => 'ABC1234',
            'class'      => Group::class,
            'reference'  => 1
        ]);
        $tokens->create([
            'code'       => 'XYZ4321',
            'class'     => Group::class,
            'reference'  => 2
        ]);

        $this->assertCount(2, $tokens->all());

        $claim_code = 'ABC1234';

        $contact = App::make(ContactRepository::class)->skipPresenter()->create([
            'mobile' => '09173011987',
            'handle' => "lbhurtado"
        ]);

        $object = $tokens->claim($contact, $claim_code);
        $group = App::make(GroupRepository::class)->skipPresenter()->find($object->id);

        $this->assertInstanceOf(Group::class, $object);
        $this->assertEquals($group->name, $object->name);
        $this->assertCount(1, $tokens->all());

        $token = $tokens->first();

        $this->assertEquals('XYZ4321', $token->code);
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

        $contact = $contacts->create([
            'mobile' => '09173011987',
            'handle' => "lbhurtado"
        ]);

        $token = $tokens->find(1);

        $group_object = $contact->consumeToken($token->code);

        $relation = $group_object->getTable();// get table name of relationship

        $this->assertEquals(
            $group_object->name,
            $contact->$relation()->find($group_object->id)->name
        );
    }
}
