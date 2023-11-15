<?php

namespace mmerlijn\msgEdifact32\tests\Unit;



use mmerlijn\msgEdifact32\tests\TestCase;
use mmerlijn\msgEdifact32\validation\Validator;

class ValidatorTest extends TestCase
{

    public function test_basic_validation()
    {
        $data = [
            'bsn' => "123456789",
            "agbcode" => "1234"
        ];
        $rules = [
            'bsn' => ["required", "length:9"],
            'agbcode' => "required|length:8"
        ];
        Validator::reset();
        $result = Validator::validate($data, $rules);
        $this->assertFalse($result);
        $this->assertStringContainsString("agbcode", Validator::$errors[0]);
    }

    public function test_required_rule()
    {
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'required']);
        $this->assertTrue($result);
        Validator::reset();
        $result = Validator::validate(['name' => ""], ['name' => 'required']);
        $this->assertFalse($result);
    }

    public function test_length_rule()
    {
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'length:2']);
        $this->assertFalse($result);
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'length:3']);
        $this->assertTrue($result);
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'length:5']);
        $this->assertFalse($result);
    }

    public function test_max_rule()
    {
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'max:2']);
        $this->assertFalse($result);
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'max:3']);
        $this->assertTrue($result);
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'max:5']);
        $this->assertTrue($result);
    }

    public function test_min_rule()
    {
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'min:2']);
        $this->assertTrue($result);
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'min:3']);
        $this->assertTrue($result);
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'min:5']);
        $this->assertFalse($result);
    }

    public function test_between_rule()
    {
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'between:2,4']);
        $this->assertTrue($result);
        Validator::reset();
        $result = Validator::validate(['name' => "Bobbie"], ['name' => 'between:3,4']);
        $this->assertFalse($result);
        Validator::reset();
        $result = Validator::validate(['name' => "Bob"], ['name' => 'between:,4']);
        $this->assertTrue($result);
        $result = Validator::validate(['name' => "Bob"], ['name' => 'between:2']);
        $this->assertTrue($result);
    }

    public function test_in_rule()
    {
        Validator::reset();
        $result = Validator::validate(['item' => "ak"], ['item' => 'in:ak,az,ca']);
        $this->assertTrue($result);
        Validator::reset();
        $result = Validator::validate(['item' => "ak"], ['item' => 'in:ab,az,ca']);
        $this->assertFalse($result);
    }

    public function test_with_message()
    {
        Validator::reset();
        $result = Validator::validate(['name' => ""], ['name' => 'required'], ['name' => '@ Field PAT 2,4']);
        $this->assertStringContainsString("Field PAT 2,4", Validator::firstError());
    }
}