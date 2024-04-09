<?php

namespace mmerlijn\msgEdifact32\tests\Feature;

use Carbon\Carbon;
use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Contact;
use mmerlijn\msgRepo\Enums\PatientSexEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Name;
use mmerlijn\msgRepo\Order;
use mmerlijn\msgRepo\Patient;
use mmerlijn\msgRepo\Phone;
use mmerlijn\msgRepo\Result;
use PHPUnit\Framework\TestCase;

class Edifact32Test extends TestCase
{
    public function test_write()
    {
        $repo = new Msg();
        $repo->processing_id = 'T12345678';
        $repo->id = '12345678';
        $repo->setPatient(new Patient(sex: PatientSexEnum::MALE, name: new Name(initials: 'J', lastname: 'Jansen', prefix: 'van', own_lastname: 'Groot', own_prefix: 'de'), dob: Carbon::create('2000-01-01'), bsn: '123456782', address: new Address(postcode: '1234AB', city: 'Stad', street: 'Straatnaam', building: '1a'), phones: [new Phone(number: 'nb')]));
        $repo->setReceiver(new Contact(agbcode: '012345678', name: new Name(initials: 'P',own_lastname: 'Huisarts'), source: 'VEKTIS', address: new Address(postcode: '9988AB', city: 'City', street: 'Street', building: '1a')));
        $repo->setSender(new Contact(agbcode: '011212121', name: new Name(own_lastname: 'Salt'), address: new Address(postcode: "1040AA", city: 'Amsterdam', street: 'HStreet', building: "2b"), phone: '0612345678'));
        $order = new Order(
            request_nr: 'ZD12345678',
            lab_nr: '012345',
            requester: new Contact(agbcode: '012345678', name: new Name(initials: 'P',own_lastname: 'Huisarts'), source: 'VEKTIS', address: new Address(postcode: '9988AB', city: 'City', street: 'Street', building: '1a')),
            dt_of_observation: Carbon::now(),
        );
        $order->addResult(new Result(
            type_of_value: 'TV', value: '*', test_code: 'FUND', test_name: 'FUND'
        ));
        $order->addResult(new Result(
            type_of_value: 'CV', value: '386', test_code: 'FSFUFZ', test_name: 'advFUfund'
        ));
        $repo->setOrder($order);
        $mrpt = new Edifact32();
        $mrpt->setMsg($repo);
        $mrpt->setSegmentValue('UNB', 0, '011212121', 2);
        $mrpt->setSegmentValue('UNB', 0, '011212121', 3);
        $output = $mrpt->write();
        $this->assertStringNotContainsString("COM+nb:TE",  $output); //phone number is nb
        $this->assertStringContainsString("COM+0612345678:TE",  $output);
        $this->assertStringContainsString("UNB+UNOA:1+011212121+011212121+",  $output);
        $this->assertStringContainsString("UNH+12345678+MEDRPT:D:93A:UN:MRPN32'",  $output);
        $this->assertStringContainsString("NAD+SLA+011212121:CLB:VEK++KS :SALT:::012345678'",  $output);
        $this->assertStringContainsString("NAD+PO+012345678:CGP:VEK++Huisarts:P+Street+1:a+City+9988AB'",  $output);
    }
}
