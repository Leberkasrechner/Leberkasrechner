<?php
require "vendor/autoload.php";
use Spatie\OpeningHours\OpeningHours;
use Ujamii\OsmOpeningHours\OsmStringToOpeningHoursConverter;

class Butcher {
    private $id;
    private $tags;

    public function __construct($id, $lat, $lon, $tags) {
        $this->id = $id;
        $this->tags = json_decode(utf8_encode($tags), true);;
        $this->house = null;
        $this->street = null;
        $this->city = null;
        $this->address = null;
        $this->formatAddress();
    }

    # TODO: Konstruktor, der nur $id als Eingabewert hat und dann die Daten
    # automatisch fetcht

    private function formatAddress() {
        $this->house = $this->street = $this->city = null;
        if (!(empty($this->tags["addr:housenumber"]) && empty($this->tags["addr:housename"]))) {
            $this->house = $this->tags["addr:housenumber"] ?? $this->tags["addr:housename"];
        }
        if (!(empty($this->tags["addr:place"]) && empty($this->tags["addr:street"]))) {
            $this->street = $this->tags["addr:place"] ?? $this->tags["addr:street"];
        }
        if (!empty($this->tags["addr:city"])) {
            $this->city = $this->tags["addr:city"];
            if (!empty($this->tags["addr:postcode"])) {
                $this->city = $this->tags["addr:postcode"] . " " . $this->city;
            }
        }
        $this->address = "";
        if (!empty($this->house)) { $this->address .= $this->house; }
        if (!empty($this->street)) { 
            $this->address = $this->street . " " . $this->address; 
        }
        if (!empty($this->city)) { 
            $this->address .= ", " . $this->city; 
        }
    }

    public function getName() {
        if(empty($this->tags["name"])) {
            return "Metzger";
        } else {
            return $this->tags["name"];
        }
    }

    public function getOpeningStateHTML($colored=true) {
        if(empty($this->tags["opening_hours"])||strlen($this->tags["opening_hours"]<3)) {return "";}
        $ret = "";
        try {
            $opstr = preg_replace('/(,|;)\s+/', '$1', $this->tags["opening_hours"]); # Leerzeichen nach komma oder semikolon entfernen
            $opstr = preg_replace('/\b(\d{1}:\d{2})\b/', '0$1', $opstr); # aus 6:30 mach 06:30

            $openingHours = OsmStringToOpeningHoursConverter::openingHoursFromOsmString($opstr);
            $now = new DateTime('now');
            $range = $openingHours->currentOpenRange($now);
        } catch (Exception $e) {
            return $this->tags["opening_hours"];
        }

        if($range) {
            $ret = "<span class='text-lime'>Geöffnet</span>";
        } else {
            $ret = "<span class='text-red'>Geschlossen</span>";
        }

        /*
        if ($range) {
            $ret .= "It's open since ".$range->start()."\n";
            $ret .= "It will close at ".$range->end()."\n";
        } else {
            $ret .= "It's closed since ".$openingHours->previousClose($now)->format('l H:i')."\n";
            $ret .= "It will re-open at ".$openingHours->nextOpen($now)->format('l H:i')."\n";
        }*/
        return $ret; 
    }

    public function getId() {
        return $this->id;
    }

    public function getTags() {
        return json_decode($this->tags, true);
    }
}