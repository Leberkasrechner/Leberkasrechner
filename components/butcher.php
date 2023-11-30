<?php
require "vendor/autoload.php";
use Spatie\OpeningHours\OpeningHours;
use Ujamii\OsmOpeningHours\OsmStringToOpeningHoursConverter;

class Butcher {
    private $id;
    public $tags;

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

    public function getInfoCard() {
        ## INFO CARD ######################################
        $ret = "";
        $svgs = array(
            "website" => '<path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" />',
            "addr:city" => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18.364 4.636a9 9 0 0 1 .203 12.519l-.203 .21l-4.243 4.242a3 3 0 0 1 -4.097 .135l-.144 -.135l-4.244 -4.243a9 9 0 0 1 12.728 -12.728zm-6.364 3.364a3 3 0 1 0 0 6a3 3 0 0 0 0 -6z" stroke-width="0" fill="currentColor" /></svg>',
            "opening_hours" => "<path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0' /><path d='M12 12h3.5' /><path d='M12 7v5' />",
            "phone" => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>',
            "email" => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>',
            "" => '',
        );
        $htmlfields = array(
            "addr:city" => str_replace(",", ",<br>", $this->address),
            "website" => '<a href="{v}">{v}</a>',
            "opening_hours" => $this->getOpeningStateHTML(),
            "phone" => "<a href='tel:{v}'>{v}</a>",
            "email" => "<a href='mailto:{v}'>{v}</a>"
        );
        $aliases = array(
            "contact:phone" => "phone",
            "contact:website" => "website",
            "contact:email" => "email",
        );
        foreach (array_keys($this->tags) as $key) {
            $key = array_key_exists($key, $aliases) ? $aliases[$key] : $key; # Wenn der Key nur als Alias angesehen wird, dann nehme die kürzere Version
            $value = $this->tags[$key];
        
            if (array_key_exists($key, $htmlfields)) {
                $ret .= '<div class="d-flex align-items-center mt-2">
                                <span class="avatar avatar-s me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">';
                $ret .= $svgs[$key] . "</svg></span>";
                $ret .= str_replace("{v}", $value, $htmlfields[$key]);
                $ret .= "</div>";
            }

        }
        return $ret;
    }


    public function getId() {
        return $this->id;
    }

    public function getTags() {
        return json_decode($this->tags, true);
    }
}