# HL7

Read and write HL7 messages from and to array

### Requirements

```php >=8.1```

### Installation

``` composer require mmerlijn/msg-edifact32```

### Writing messages
AANPASSEN!!!!!!!!!!!!!!!!!!
```php
// fill the msg repository
$msg = new Msg();
$msg->sender->application = "Application name";
$msg->sender->facility = "Facility name";
$msg->receiver->application = "Application name";
$msg->receiver->facility = "Facility name";
$msg->datetime = Carbon::now(); //default current datetime
$msg->security_id = ""; //optional
$msg->msgType->type = "ORM";
$msg->msgType->trigger = "001";
$msg->msgType->structure = "ORM_001";
$msg->id = "abc123"; //unique message id
$msg->msgType->version="2.4"; //default

//Patient data
$msg->patient->addId(new Id(id:"123456782",type:"bsn"));
$msg->patient->setName(new Name(
    own_lastname:"Doe",initials:"J"
));
$msg->patient->setSex("M");
$msg->patient->dob = Carbon::create("2000-10-05");
$msg->patient->setAddress(new Address(
   street: "Long Street",building: "14a",city: "Amsterdam",postcode: "1040AA"
   ));
$msg->patient->addPhone("0612341234");
$msg->patient->setInsurance(new Insurance(
            company_name: "CC Comp",
            policy_nr: "01234123124",
            uzovi: "1234",
        ));

//order data        
$msg->order->admit_reason_code = "ABC";
$msg->order->admit_reason_name = "Xohabia";

$msg->order->control ="NEW"; //NEW / CANCEL / CHANGE / RESULT
$msg->order->request_nr = "AB123123123";
$msg->priority = false; 
$msg->db_of_request = Carbon::now();
$msg->order->requester->agbcode = "0123456";
$msg->order->requester->setName(new Name(own_lastname: 'Arts',initials:"RP"));;
$msg->order->requester->source = "VEKTIS";

//requests
$msg->order->addRequest(new Request(
    test_code: "BBB", test_name: "Blubber"
));
$msg->order->where = "home"; // home=>L / other / else =>O

//result
$msg->order->addResult(new Result(
    type_of_value:"ST", //optional ST/NM/CE/FT
    test_code: "CCC",
    test_name: "Circular",
    value: "true",
    done: true, //final value
    change:false,
));
$msg->order->dt_of_observation = Carbon::create(...);
$msg->order->dt_of_analysis = Carbon::create(...);

//comments
$msg->addComment("Hello World"); //belongs to msg

$msg->order->requests->addComment("Hello Day"); // comment on request

$msg->order->result->addComment("Good morning") // comment on result


//create HL7 instance
$hl7 = new \mmerlijn\msgHl7\Hl7();

//setting the data
$hl7->setDatetimeFormat("YmdHis") //option (how to write datetime values 
    ->setRepeatORC() //option default only ones
    ->setMsg($msg);

//
try{
  echo $hl7->write(true); //with or without validation of required fields
}catch(\Exception $e){
   echo $e;
}
```

It is also possible to start with a template and add/overwrite msg data afterwards

### Getting message

```php
//init instance
$hl7 = new \mmerlijn\msgHl7\Hl7("MSH ....");

//or
$hl7 = new \mmerlijn\msgHl7\Hl7();
$hl7->read("MSH...");

//read data to repository
$msg = $hl7->getMsg(new Msg());

```

### Result

```php

```