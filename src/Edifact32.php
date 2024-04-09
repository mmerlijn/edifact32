<?php

namespace mmerlijn\msgEdifact32;

use mmerlijn\msgEdifact32\segments\ADR;
use mmerlijn\msgEdifact32\segments\BGM;
use mmerlijn\msgEdifact32\segments\COM;
use mmerlijn\msgEdifact32\segments\CTA;
use mmerlijn\msgEdifact32\segments\DTM;
use mmerlijn\msgEdifact32\segments\FCA;
use mmerlijn\msgEdifact32\segments\FTX;
use mmerlijn\msgEdifact32\segments\INV;
use mmerlijn\msgEdifact32\segments\NAD;
use mmerlijn\msgEdifact32\segments\PDI;
use mmerlijn\msgEdifact32\segments\PNA;
use mmerlijn\msgEdifact32\segments\RFF;
use mmerlijn\msgEdifact32\segments\RND;
use mmerlijn\msgEdifact32\segments\RSL;
use mmerlijn\msgEdifact32\segments\S01;
use mmerlijn\msgEdifact32\segments\S02;
use mmerlijn\msgEdifact32\segments\S04;
use mmerlijn\msgEdifact32\segments\S06;
use mmerlijn\msgEdifact32\segments\S07;
use mmerlijn\msgEdifact32\segments\S16;
use mmerlijn\msgEdifact32\segments\S18;
use mmerlijn\msgEdifact32\segments\S20;
use mmerlijn\msgEdifact32\segments\SPC;
use mmerlijn\msgEdifact32\segments\STS;
use mmerlijn\msgEdifact32\segments\UNB;
use mmerlijn\msgEdifact32\segments\UNH;
use mmerlijn\msgEdifact32\segments\UNT;
use mmerlijn\msgEdifact32\segments\UNZ;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Msg;

class Edifact32
{
    private string $msg = "";
    public string $type = "MEDRPT";
    public array $segments = [];

    public function __construct(string $edifact = "")
    {
        if ($edifact) {
            $this->msg = $edifact;
            $this->buildSegments();
        }

        return $this;
    }

    public function read(string $edifact): self
    {
        $this->msg = $edifact;
        $this->buildSegments();
        return $this;
    }

    public function write(bool $validate = false): string
    {
        Validator::reset();
        $output = "";
        foreach ($this->segments as $teller => $segment) {
            if ($validate)
                $segment->validate();
            if ($segment->name == "UNT")
                $segment->setData($teller, 1);
            $output .= $segment->write() . "'" . chr(13);
        }
        if (Validator::fails()) {
            throw new \Exception("Edifact32 validation fails: " . PHP_EOL . implode(PHP_EOL, Validator::getErrors()));
        }
        return $output;
    }

    public function getMsg(Msg $msg): Msg
    {
        foreach ($this->segments as $segment) {
            $msg = $segment->getMsg($msg);
        }
        return $msg;
    }

    public function setMsg(Msg $msg): self
    {
        $this->type = $msg->msgType->type ?: "MEDRPT";

        if (empty($this->segments)) {
            $this->createDefaultSegments();
        }
        foreach ($this->segments as $k => $segment) {
            $this->segments[$k]->setMsg($msg);
        }
        //set results
        if (!empty($msg->order->results)) {
            $RND_counter = 1;
            $result_counter = 1;
            $start_segment_teller = $this->findSegmentKey("SPC") + 2;
            foreach ($msg->order->results as $k => $result) {
                array_splice($this->segments, $start_segment_teller, 0, [(new S18("S18+$result_counter+G"))]);
                $start_segment_teller++;
                $result_counter++;
                array_splice($this->segments, $start_segment_teller, 0, [(new INV("INV+1+:AMB:NHG:"))->setResult($result)]);
                $start_segment_teller++;
                array_splice($this->segments, $start_segment_teller, 0, [(new RSL("RSL+++++"))->setResult($result)]);
                $start_segment_teller++;
                $text = [];
                //adding comments
                foreach ($result->comments as $comment) {
                    $text = array_merge($text, $this->trimStringToArray($comment, 70));
                }
                $length = count($text);
                if ($length > 45) {
                    $length = 45;
                }
                for ($s = 0; $s < $length; $s += 5) { //5 regels per FTX
                    $row = [];
                    for ($j = 0; $j < 5; $j++) {
                        if (isset($text[($s + $j)])) {
                            $row[] = $text[($s + $j)];
                        }
                    }
                    if (sizeof($row)) {
                        array_splice($this->segments, $start_segment_teller, 0, [(new FTX("FTX+UIT+++"))->setText($row)]);
                        $start_segment_teller++;
                    }
                }
                if ($result->reference_range) { //will be split by space
                    array_splice($this->segments, $start_segment_teller, 0, [(new S20("S20+" . $RND_counter))]);
                    $start_segment_teller++;
                    array_splice($this->segments, $start_segment_teller, 0, [(new RND("RND+RU++"))->setRange($result->reference_range)]);
                    $start_segment_teller++;
                    $RND_counter++;

                }
            }
        }
        //kopie arts
        if($msg->order->copy_to){
            $start_segment_teller = $this->findSegmentKey("CTA") + 3;
            array_splice($this->segments, $start_segment_teller, 0, [new S01("S01+3")]);
            $start_segment_teller++;
            array_splice($this->segments, $start_segment_teller, 0, [(new NAD("NAD+CCR+:CGP:VEK++::++:++"))->setContact($msg->order->copy_to)]);
        }
        return $this;
    }
    //helper function to set specific segment values
    public function setSegmentValue(string $SEG, int $position, string $value, int $component, int $item = 0): self
    {
        $key = $this->findSegmentKey($SEG,$position);
        if ($key < count($this->segments)) {
            $this->segments[$key]->setData($value, $component, $item);
        }
        return $this;
    }

    //search for first segment occurrence
    public function findSegmentKey(string $SEG, int $position=0)
    {
        $teller = 0;
        foreach ($this->segments as $k => $segment) {
            if ($segment->name == $SEG) {
                if ($teller == $position) {
                    return $k;
                }
                $teller++;
            }
        }
        return count($this->segments);
    }

    protected function buildSegments(): void
    {
        $this->segments = [];
        $lines = preg_split("/(?<!\?)'/", trim($this->msg));
        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line)) {
                $segment = 'mmerlijn\\msgEdifact\\segments\\' . substr($line, 0, 3);
                if (class_exists($segment)) {
                    $this->segments[] = new $segment($line);
                } else {
                    $this->segments[] = new Undefined($line);
                }
            }
        }
    }

    //MEDLAB
    protected function createDefaultSegments()
    {
        if ($this->type == "MEDRPT") {
            $this->segments = [
                new UNB("UNB+UNOA:1++++"),
                new UNH("UNH++MEDRPT:D:93A:UN:MRPN32"),
                new BGM("BGM+LRP:MF:ITN++9+NA"),
                new DTM("DTM+137:"),
                new S01("S01+1"),
                new NAD("NAD+SLA+:CLB:VEK++KS :SALT:::"),
                new ADR("ADR++1::+++NL",['group'=>1]),
                new COM("COM+",['type'=>'sender']),
                new CTA("CTA+AFD+:Biometrie"),

                new S01("S01+2"),
                new NAD("NAD+PO+:CGP:VEK++::++:++"),

                new S02("S02+1+N"),

                new RFF("RFF+SRI:"),
                new STS("STS++G"),
                new DTM("DTM+ISR::203"),

                new S04("S04+1+N"),

                new FCA("FCA+PU+:ZZ:VEK:"),
                new RFF("RFF+ROI:"),
                new DTM("DTM+ISO::203"),
                new DTM("DTM+ISO::203"), //ja twee keer anders werkt het niet

                new S06("S06+1"),
                //gegevens van patient
                new ADR("ADR++1::+++NL",['group'=>6]),
                new COM("COM+",['type'=>'patient']),

                new S07("S07+1"),
                new PNA("PNA+PAT+:::PCL:LZB+++++++"),
                new DTM("DTM+329::102"), //dob
                new PDI("PDI+"),

                new S16("S16+1"),
                new SPC("SPC+TSP"),
                new DTM("DTM+SCO::203"), //monster

                //uitslagen



                new UNT("UNT++"),
                new UNZ("UNZ+1+M"),
            ];
        }
    }

    //Voor tekst comments
    protected function trimStringToArray(string $string, int $inkort_lengte=70):array
    {
        $return = array();
        $a_string = explode(" ", $string);
        $temp_string = "";
        foreach ($a_string as $woord) {
            if ($woord == "<space>" || $woord=='<spatie>') {
                $woord = " ";
            }
            if ($woord == "<tab>") {
                $woord = "    ";
            }
            if ($woord == "<br>") {
                $return[] = $temp_string;
                $temp_string = "";
            } else {
                if (strlen($temp_string) + strlen($woord) + 1 > $inkort_lengte) {
                    $return[] = $temp_string;
                    $temp_string = $woord;
                } else {
                    if (strlen($temp_string)) {
                        $temp_string .= " " . $woord;
                    } else {
                        $temp_string .= $woord;
                    }
                }
            }
        }
        if (strlen($temp_string)) {
            $return[] = $temp_string;
        }
        return $return;
    }
}