<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\ContactRepository;
use App\Entities\Contact;
use libphonenumber\PhoneNumberFormat;
use App\Repositories\ShortMessageRepository;

class ContactTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function contact_has_mobile_handle()
    {
        $contact = App::make(ContactRepository::class)->skipPresenter()->create([
            'mobile' => '09173011987',
            'handle' => "lbhurtado"
        ]);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('+639173011987', $contact->mobile);
        $this->assertEquals('lbhurtado', $contact->handle);
        $this->seeInDatabase($contact->getTable(), [
            'mobile'  => '+639173011987',
            'handle'  => 'lbhurtado',
        ]);
    }

    /** @test */
    function contact_mobile_field_is_required()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ContactRepository::class)->create([
            'handle' => 'lbhurtado',
        ]);
    }

    /** @test */
    function contact_mobile_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ContactRepository::class)->create([
            'mobile'  => '',
            'handle' => 'lbhurtado',
        ]);
    }

    /** @test */
    function contact_mobile_field_is_a_mobile_number()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ContactRepository::class)->create([
            'mobile' => '1234',
            'handle' => "lbhurtado"
        ]);
    }

    /** @test */
    function contact_handle_is_mobile_if_blank()
    {
        $contact = App::make(ContactRepository::class)->skipPresenter()->create([
            'mobile' => '09173011987'
        ]);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('+639173011987', $contact->handle);
        $this->seeInDatabase($contact->getTable(), [
            'mobile'  => '+639173011987',
            'handle'  => '+639173011987',
        ]);
    }

    /** @test */
    function contact_factory_is_valid(){
        $sms = factory(Contact::class)->create();
        $validator = Validator::make(
            $sms->all()->toArray(),
            [
                'mobile' => 'phone:PH',
            ]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    function contact_has_a_presenter()
    {
        $contact = App::make(ContactRepository::class)->create([
            'mobile' => '09173011987',
            'handle' => 'lbhurtado',
        ]);

        $this->assertEquals(
            phone_format('09173011987', 'PH', PhoneNumberFormat::E164),
            $contact['data']['mobile']
        );
    }

    /** @test */
    function contact_has_unique_mobile_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        App::make(ContactRepository::class)->create([
            'mobile' => '09173011987',
            'handle' => 'lbhurtado',
        ]);

        App::make(ContactRepository::class)->create([
            'mobile' => '09173011987',
            'handle' => 'raphurtado',
        ]);
    }

    /** @test */
    function contact_has_unique_handle_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        App::make(ContactRepository::class)->create([
            'mobile' => '09173011987',
            'handle' => 'lbhurtado',
        ]);

        App::make(ContactRepository::class)->create([
            'mobile' => '09189362340',
            'handle' => 'lbhurtado',
        ]);
    }

    /** @test */
    function contact_mobile_attribute_is_formatted()
    {
        $contact = new Contact();
        $contact->mobile = '09173011987';

        $this->assertEquals('+639173011987', $contact->mobile);
    }

    /** @test */
    function contact_has_many_short_messages()
    {
        $short_messages = App::make(ShortMessageRepository::class)->skipPresenter();

        $short_messages->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ]);

        $short_messages->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "jumps over the lazy dog...",
            'direction' => INCOMING
        ]);

        $short_messages->skipPresenter()->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ]);

        $this->assertCount(3 , $short_messages->all());

        $contacts = App::make(ContactRepository::class)->skipPresenter();

        $contact = $contacts->findWhere(['mobile' => '+639173011987'])->first();

        $this->assertCount(2, $contact->short_messages);

        $this->assertEquals(
            [
                "The quick brown fox...",
                "jumps over the lazy dog..."
            ],
            $contact->short_messages->pluck('message')->toArray()
        );
    }
}
