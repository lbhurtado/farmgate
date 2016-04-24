<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\ContactRepository;
use App\Entities\Contact;
use libphonenumber\PhoneNumberFormat;

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
        $this->assertEquals('09173011987', $contact->mobile);
        $this->assertEquals('lbhurtado', $contact->handle);
        $this->seeInDatabase($contact->getTable(), [
            'mobile'  => '09173011987',
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
}
