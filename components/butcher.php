

<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Spatie\OpeningHours\OpeningHours;
    use Ujamii\OsmOpeningHours\OsmStringToOpeningHoursConverter;
     
    class Butcher {
        private $id;
        public $tags;
        private $opening_hours;
        private $opening_hours_available;
        private $opening_hours_check_date;
        public $diet_info_available;
     
        public function __construct($id, $lat, $lon, $tags, $oh=true) {
            $this->id = $id;
            $this->tags = json_decode(utf8_encode($tags), true);;
            $this->house = null;
            $this->street = null;
            $this->city = null;
            $this->address = null;
            $this->diet_info_available = false;
            $this->getDietInfoHTML();
            # Opening Hours
            if(!empty($this->tags["check_date:opening_hours"])) {
                $this->opening_hours_check_date = $this->tags["check_date:opening_hours"];
            }
            $this->formatAddress();
            if($oh) {$this->renderOpeningHours();}
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

        public function getDietInfoHTML() {
            $ret = "";
            $texts = Array(
                "diet:vegan" => Array(
                    "icon" => "carrot",
                    "yes" => Array(
                        "type" => "positive",
                        "description" => "Hier werden vegane Lebensmittel angeboten."
                    ),
                    "only" => Array(
                        "type" => "positive",
                        "description" => "Hier werden <b>nur</b> vegane Lebensmittel angeboten."
                    ),
                    "no" => Array(
                        "type" => "negative",
                        "description" => "Hier werden <b>keine</b> veganen Lebensmittel angeboten."
                    )
                ),
                "diet:vegetarian" => Array(
                    "icon" => "carrot",
                    "yes" => Array(
                        "type" => "positive",
                        "description" => "Hier werden vegetarische Lebensmittel angeboten."
                    ),
                    "only" => Array(
                        "type" => "positive",
                        "description" => "Hier werden <b>nur</b> vegetarische Lebensmittel angeboten."
                    ),
                    "no" => Array(
                        "type" => "negative",
                        "description" => "Hier werden <b>keine</b> vegetarische Lebensmittel angeboten."
                    )
                ),
                "diet:halal" => Array(
                    "icon" => "carrot",
                    "yes" => Array(
                        "type" => "positive",
                        "description" => "Hier werden <b>halal</b>e Lebensmittel angeboten."
                    ),
                    "only" => Array(
                        "type" => "positive",
                        "description" => "Hier werden <b>nur halal</b>e Lebensmittel angeboten."
                    ),
                    "no" => Array(
                        "type" => "negative",
                        "description" => "Hier werden <b>keine</b> <b>halal</b>en Lebensmittel angeboten."
                    )
                ),
                "diet:kosher" => Array(
                    "icon" => "carrot",
                    "yes" => Array(
                        "type" => "positive",
                        "description" => "Hier werden koscherene Lebensmittel angeboten."
                    ),
                    "only" => Array(
                        "type" => "positive",
                        "description" => "Hier werden <b>nur koschere</b> Lebensmittel angeboten."
                    ),
                    "no" => Array(
                        "type" => "negative",
                        "description" => "Hier werden <b>keine koscheren</b> Lebensmittel angeboten."
                    )
                ),
            );        
            $icons = Array(        
                "negative" => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-red" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>',
                "positive" => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l5 5l10 -10"></path></svg>',
            );
                foreach($texts as $key => $meta) {
                #$meta["icon"]
                if(!array_key_exists($key, $this->tags)) {continue;}
                $this->diet_info_available = true;
                $osmvalue = $this->tags[$key];
                $ret .= "<li>";
                $ret .= $icons[$meta[$osmvalue]["type"]];
                $ret .= "&nbsp;" . $meta[$osmvalue]["description"] . "</li>";
            }
            return $ret;
        }
     
        private function renderOpeningHours() {
            # Turns OSM opening_hours tag into an spatie/opening_hours object for further use
            
            if(empty($this->tags["opening_hours"])||strlen($this->tags["opening_hours"]<3)) {return "";}
            $ret = "";
            try {
                $opstr = preg_replace('/(,|;)\s+/', '$1', $this->tags["opening_hours"]); # Leerzeichen nach komma oder semikolon entfernen
                $opstr = preg_replace('/\b(\d{1}:\d{2})\b/', '0$1', $opstr); # aus 6:30 mach 06:30
                set_error_handler(function() { /* ignore errors */ });
                try {
                    $this->opening_hours = OsmStringToOpeningHoursConverter::openingHoursFromOsmString($opstr);
                } catch (TypeError $e) {
                    // Error/Warning while trying to parse opening hours; nothing is done here
                    // and the availability state of the opening hours is beeing set to 0
                    $this->opening_hours_available = false;
                }
                restore_error_handler();
                $this->opening_hours_available = true;
            } catch (Exception $e) {
                # opening hours could not be parsed
                $this->opening_hours_available = false;
                $this->opening_hours = null;
                return null;
            }
        }
     
        public function getOpeningState() {
            # Sendet true wenn geöffnet, false wenn geschlossen
            if(!$this->opening_hours_available) {return null;}
            
            $now = new DateTime('now');
            $range = $this->opening_hours->currentOpenRange($now);
     
            if($range) {
                return true;
            } else {
                return false;
            }
        }
     
        public function getOpeningStateHTML() {
            # Sendet HTML-Text, "Geöffnet" oder "Geschlossen"
            if(!$this->opening_hours_available) {return null;}
            $ret = "";
            
            $now = new DateTime('now');
            try {
                $range = $this->opening_hours->currentOpenRange($now);
            } catch (Error $e) {
                return false;
            }
     
            if($range) {
                $ret = "<span class='text-lime'>Geöffnet</span>";
            } else {
                $ret = "<span class='text-red'>Geschlossen</span>";
            }
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
                "operator" => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>',
                "" => '',
            );
            $htmlfields = array(
                "addr:city" => str_replace(",", ",<br>", $this->address),
                "website" => '<a href="{v}">{v}</a>',
                "opening_hours" => $this->getOpeningStateHTML(),
                "phone" => "<a href='tel:{v}'>{v}</a>",
                "email" => "<a href='mailto:{v}'>{v}</a>",
                "operator" => "{v}",
            );
            $aliases = array(
                "contact:phone" => "phone",
                "contact:website" => "website",
                "contact:email" => "email",
            );
            foreach (array_keys($this->tags) as $key) {
                $value = $this->tags[$key];
                $key = array_key_exists($key, $aliases) ? $aliases[$key] : $key; # Wenn der Key nur als Alias angesehen wird, dann nehme die kürzere Version
            
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
     
        public function getOpeningHoursHTML() {
            $daydic = Array(
                "monday" => "Montag",
                "tuesday" => "Dienstag",
                "wednesday" => "Mittwoch",
                "thursday" => "Donnerstag",
                "friday" => "Freitag",
                "saturday" => "Samstag",
                "sunday" => "Sonntag"
            );
            $ret = "";
            if(!$this->opening_hours_available) {return false;}
            $ret .= '<div class="table-responsive"><table class="table table-vcenter table-hover card-table">';
            $ret .= '<thead><tr><th>Tag</th><th>Öffnungszeiten</th></tr></thead>';
            foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
                $ret .= '<tr>';
                $bold = (strtolower((new DateTime())->format('l')) == $day);
                if($bold) {
                    $ret .= '<td><b>' . ucfirst($daydic[$day]) . '</b></td>';
                } else {
                    $ret .= '<td>' . ucfirst($daydic[$day]) . '</td>';
                }
                $ret .= '<td>';
                if($bold) {$ret .= "<b>";}
                $ret .= implode(', ', $this->opening_hours->forDay($day)->map(function ($timeRange) {
                    return $timeRange->format('H:i');
                }));
                if($bold) {$ret .="</b>";}
                $ret .= '</td>';
                $ret .= '</tr>';
            }
            $ret .= '</table></div>';
            return $ret;
        }
     
        public function getOpeningHoursCheckDate() {
            if(empty($this->opening_hours_check_date)) {return null;}
            try {
                return (new DateTime($this->opening_hours_check_date))->format('d.m.Y');
            } catch (Exception $e) {
                return null;
            }
        }

        public function getImageIDs($conn) {
            $butcherId = $this->getId();
            // SQL-Abfrage, um die Bilder-IDs für den gegebenen Metzger zu erhalten
            $sql = "SELECT i.id 
                    FROM image i
                    INNER JOIN image_butcher ib ON i.id = ib.image
                    WHERE ib.butcher = $butcherId";
        
            $result = $conn->query($sql);
        
            if (!$result) {
                return false;
            }
        
            $imageIds = array();
        
            while ($row = $result->fetch_assoc()) {
                $imageIds[] = $row['id'];
            }
    
            return $imageIds;
        }
     
        public function getId() {
            return $this->id;
        }
     
        public function getTags() {
            return json_decode($this->tags, true);
        }
    }