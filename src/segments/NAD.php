<?php

namespace mmerlijn\msgEdifact32\segments;

use Carbon\Carbon;
use mmerlijn\msgEdifact32\segments\Segment;
use mmerlijn\msgEdifact32\segments\SegmentInterface;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Contact;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Name;

class NAD extends Segment implements SegmentInterface
{
    /*
010    3035 PARTY FUNCTION CODE QUALIFIER              M    1 an..3=>CCR=copy destination, PAT=patient, PO=de AANVRAGER van het lab.diagn. onderzoek (AAN), SLA=de uitvoerende partij (VAN)

020    C082 PARTY IDENTIFICATION DETAILS               C    1
       3039  Party identifier                          M      an..35
       1131  Code list identification code             C      an..17
       3055  Code list responsible agency code         C      an..3

030    C058 NAME AND ADDRESS                           C    1
       3124  Name and address description              M      an..35
       3124  Name and address description              C      an..35
       3124  Name and address description              C      an..35
       3124  Name and address description              C      an..35
       3124  Name and address description              C      an..35

040    C080 PARTY NAME                                 C    1
       3036  Party name                                M      an..70
       3036  Party name                                C      an..70
       3036  Party name                                C      an..70
       3036  Party name                                C      an..70
       3036  Party name                                C      an..70
       3045  Party name format code                    C      an..3

050    C059 STREET                                     C    1
       3042  Street and number or post office box
             identifier                                M      an..35
       3042  Street and number or post office box
             identifier                                C      an..35
       3042  Street and number or post office box
             identifier                                C      an..35
       3042  Street and number or post office box
             identifier                                C      an..35

060    3164 CITY NAME                                  C    1 an..35

070    C819 COUNTRY SUBDIVISION DETAILS                C    1
       3229  Country subdivision identifier            C      an..9
       1131  Code list identification code             C      an..17
       3055  Code list responsible agency code         C      an..3
       3228  Country subdivision name                  C      an..70

080    3251 POSTAL IDENTIFICATION CODE                 C    1 an..17

090    3207 COUNTRY IDENTIFIER                         C    1 an..3

*/
    public function getMsg(Msg $msg): Msg
    {
        if ($this->getData(1) == "SLA") {//from
            $msg->sender->agbcode = $this->getData(2, 1);
            $msg->receiver->agbcode = $this->getData(4, 5);
        }elseif ($this->getData(1)=="PO"){ //to
            $msg->receiver->agbcode = $this->getData(2, 1);
            $msg->sender->setName(new Name(
                initials: $this->getData(4,2),
                own_lastname: $this->getData(4),
                own_prefix: $this->getData(4,3)
            ));
            $msg->sender->setAddress(new Address(
                postcode: $this->getData(8),
                city: $this->getData(7),
                street: $this->getData(5),
                building_nr: $this->getData(6),
                building_addition: $this->getData(6,2)
            ));
        }
        return $msg;
    }
    public function setMsg(Msg $msg): void
    {
        if($this->getData(1)=="SLA"){ //van
            $this->setData($msg->sender->agbcode, 2)
                ->setData("CLB", 2,1)
                ->setData("VEK", 2,2)
                ->setData("KS ", 4)
                ->setData("SALT", 4,1)
                ->setData($msg->receiver->agbcode, 4,4);
        }elseif($this->getData(1)=="PO") { //aan
            $this->setContact($msg->receiver);
        }elseif($this->getData(1)=="CCR") { //kopie
            $this->setContact($msg->order->copy_to);
        }
    }
    public function setContact(Contact $contact):self
    {
        $this->setData($contact->agbcode?:"000000", 2)
            ->setData(substr($contact->name?->own_lastname ?? "",0,25), 4)
            ->setData($contact->name?->initials, 4,1)
            ->setData(substr($contact->name?->own_prefix ?? "",0,10), 4,2)
            ->setData(substr($contact->address?->street ?? "",0,24), 5)
            ->setData(substr($contact->address?->building_nr ?? "",0,9), 6)
            ->setData(substr($contact->address?->building_addition ?? "",0,8) ,6,1)
            ->setData($contact->address?->city, 7)
            ->setData($contact->address?->postcode, 8);
        return $this;
    }
}